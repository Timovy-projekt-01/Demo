# pip install owlready2 treba dat
from owlready2 import *
import os
import json
import json
import os
import shutil


def getOntology(owl_file_name):
    file_path = "owlFiles/" + owl_file_name + ".owl"
    try:
        ontology = get_ontology(file_path).load()
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


def createOntologyInConfig(owl_file_name):
    with open('fe_config.json') as json_file:
        config_data = json.load(json_file)

    try:
        ontology = getOntology(owl_file_name)
        data_properties, object_properties = parseProperties(owl_file_name)

        config_data[owl_file_name] = {
            "name": owl_file_name,
            "baseURI": ontology.base_iri,
            "ontologyPrefix": "PREFIX " + owl_file_name + ": <" + ontology.base_iri + ">",
            "searchable": [],
            "data_properties": data_properties,
            "object_properties": object_properties
        }

        # Write updated configuration to a temporary file
        temp_file = 'fe_config_temp.json'
        with open(temp_file, 'w') as json_file:
            json.dump(config_data, json_file, indent=4)

        # Replace original file with the temporary file
        shutil.move(temp_file, 'fe_config.json')
    except Exception as e:
        print("An error occurred:", str(e))
        # Clean up temporary file if it exists
        if os.path.exists(temp_file):
            os.remove(temp_file)


def main(owl_file_name):
    # Set current directory
    os.chdir('../../storage/app/ontology/')
    if owl_file_name.endswith('.owl'):
        owl_file_name = owl_file_name[:-4]

    createOntologyInConfig(owl_file_name)


if __name__ == "__main__":
    # Check if two parameters are provided
    if len(sys.argv) != 2:
        print("Usage: python propertyParser.py <owl_file_name>")
        sys.exit(1)

    # Extract parameters
    owl_file_name = sys.argv[1]
    main(owl_file_name)


