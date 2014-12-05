use RDF::RDFa::Parser;

my $url     = 'https://ping-dataportal.ucsd.edu/applications/Ontology/translate.php?query=display';
my $options = RDF::RDFa::Parser::Config->tagsoup;
my $rdfa    = RDF::RDFa::Parser->new_from_url($url, $options);

print $rdfa->opengraph('image');
print $rdfa->opengraph('description');
