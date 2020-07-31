#!/usr/bin/perl
use IO::Uncompress::Gunzip qw(gunzip $GunzipError);
use LWP::Simple;

#Boilerplate url to get papers linked to a MeSH term. Add your own API key
my $url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?&db=pubmed&api_key=524a51825dea8e8dafc5b3a2762e2af7a909&retmax=100000&term=';

#Output file
open(my $out, '>', "./mesh_papers.tsv");

#File containing list of MeSH terms with MeSH term in 1st column separated by semicolon  <Adjustable>
open(my $mesh_file, '<', './mtrees2020.bin');

#Iterate through MeSH terms
my %mesh_terms;
while ($line = <$mesh_file>){
	my @fields = split(/;/, $line);
	if (exists($mesh_terms{$fields[0]})){
		next;
	}else{
		$mesh_terms{$fields[0]} = 1;
	}
	$fields[0] =~ tr/ /_/;
	print $out $fields[0];
        $fields[0] =~ tr/_/+/;

#In the case of more than 100000 results, bool and retstart repeat the query looking at the next 100000
        my $retstart = 0;
	my $bool;
        do{
		$bool = 0;
                $output = get("$url$fields[0]" . "[mesh]" . "&retstart=$retstart");
                @output = split(/\n/, $output);
                foreach $xml (@output){
                        next if (!($xml =~ /<Id>(.*)<\/Id>/));
                        $bool = 1;
                        print $out "\t$1";
                }
                $retstart += 100000;
		sleep(.4);
        }while ($bool);
        print $out "\n";
}
close($mesh_file);
close($out);
