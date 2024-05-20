# pip install owlready2 treba dat
from owlready2 import *
import os
import json
import json
import os
import mysql.connector


def getOntology(owl_file_name):
    try:
        ontology = get_ontology(owl_file_name + '.owl').load()
        return ontology
    except FileNotFoundError:
        print("File not found")
        sys.exit(1)

def parseProperties(owl_file_name):
    ontology = getOntology(owl_file_name)

    # Delete prefix from predicates
    object_properties = {ontology.base_iri+str(prop).split('.')[-1]:str(prop).split('.')[-1] for prop in ontology.object_properties() if not isinstance(prop, ThingClass)}
    data_properties = {ontology.base_iri+str(prop).split('.')[-1]:str(prop).split('.')[-1] for prop in ontology.data_properties()}

    return data_properties, object_properties


def createOntologyInConfig(owl_file_name, connection, user_id):
    try:
        ontology = getOntology(owl_file_name)
        data_properties, object_properties = parseProperties(owl_file_name)

        config_data = {
            "name": owl_file_name,
            "baseURI": ontology.base_iri,
            "ontologyPrefix": "PREFIX " + owl_file_name + ": <" + ontology.base_iri + ">",
            "searchable": [],
            "data_properties": data_properties,
            "object_properties": object_properties
        }

        with connection.cursor(buffered=True) as cursor:
            cursor.execute('SELECT id FROM user_files WHERE user_id = %s AND name = %s', (user_id, owl_file_name + '.owl',))
            file_id = cursor.fetchone()[0]
            cursor.execute('INSERT INTO ontology_configs (name, content, user_file_id) VALUES (%s, %s, %s)', (owl_file_name, json.dumps(config_data), file_id))
            connection.commit()

    except Exception as e:
        print("An error occurred:", str(e))



def main(owl_file_name, connection, user_id):
    # Set current directory
    os.chdir('../../storage/app/ontology/owlTemplates/' + user_id + '/')
    if owl_file_name.endswith('.owl'):
        owl_file_name = owl_file_name[:-4]

    createOntologyInConfig(owl_file_name, connection, user_id)

    connection.close()


if __name__ == "__main__":
    # Check if two parameters are provided
    if len(sys.argv) != 8:
        print("Usage: python propertyParser.py <owl_file_name> <auth_user_id> <db_host> <db_user> <db_password> <db_name> <db_port>")
        sys.exit(1)

    mydb = mysql.connector.connect(
        host=sys.argv[3],
        user=sys.argv[4],
        password=sys.argv[5],
        database=sys.argv[6],
        port=sys.argv[7]
    )

    if not mydb.is_connected():
        print("Database connection failed")
        sys.exit(1)

    # Extract parameters
    owl_file_name = sys.argv[1]
    user_id = sys.argv[2]
    main(owl_file_name, mydb, user_id)


