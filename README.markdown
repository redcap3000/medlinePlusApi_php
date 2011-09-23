Ronaldo Barbachano medlinePlusApi
Sept-2011

MedlinePlus is a simple api with three methods.
All methods support output in english and spanish, and extra parameters like titles etc..
All methods return raw xml.

Developer Reference - 
http://www.nlm.nih.gov/medlineplus/connect/service.html

(simple) Example Use:

$medline = new medlinePlusApi;

$medline->get_diagnosis("250.33");

$medline->get_drug_info("637188");

$medline->get_lab_test("3187-2");

-- From http://www.nlm.nih.gov/medlineplus/faq/what.html --
The National Library of Medicine, a part of the National Institutes of Health, created and 
maintains MedlinePlus to assist you in locating authoritative health information.


MedlinePlus pages contain carefully selected links to Web resources with health information 
on over 900 topics. MedlinePlus follows a list of guidelines for the inclusion of Web sites.


The Health Topic pages include links to current news on the topic and related information. 
You can also find preformulated searches of the MEDLINE/PubMed database, which allow you to find 
references to latest health professional articles on your topic.

