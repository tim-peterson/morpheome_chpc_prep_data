#!/usr/bin/perl
use IO::Uncompress::Gunzip qw(gunzip $GunzipError);
use LWP::Simple;

#Boilerplate url to get papers linked to a list of genes. Add your own API key
my $gene_url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/elink.fcgi?dbfrom=gene&db=pubmed&api_key=524a51825dea8e8dafc5b3a2762e2af7a909&id=';

#Boilerplate url to get gene ids of homologs for a specific gene. Add your own API key
my $homolog_url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=gene&retmax=100000&api_key=524a51825dea8e8dafc5b3a2762e2af7a909&term=ortholog_gene_';

#File containing list of gene names with gene ID in the 3rd column and gene name in the 6th. <Adjustable>
open(my $gene_file, '<', './gene_result.txt');

#Output file
open(my $out, '>', './gene_papers.tsv');

#Remove the header line
my $line = <$gene_file>;

#Iterate through genes
while ($line = <$gene_file>){
	my @fields = split(/\t/, $line);
	print $out $fields[5];

#Get list of homologs
	my $homolog_output = get("$homolog_url$fields[2]" . "[group]");
	my @homolog_output = split(/\n/, $homolog_output);
	my $homolog_list = "$fields[2]";
	foreach my $homolog_xml (@homolog_output){
		next if (!($homolog_xml =~ /<Id>(.*)<\/Id>/));
		next if ($homolog_xml =~ /<Id>$fields[2]<\/Id>/);
		$homolog_list .= ",$1";
	}

#Pause because there is a limit to the rate of queries you can file
	sleep(.4);

#Get all related papers for the list of homologs
	my $paper_output = get("$gene_url$homolog_list");
	my @paper_output = split(/\n/, $paper_output);

#Iterate through gene-related papers (bool checks to confirm relative location of the line in the file)
	my $bool = 1;
	foreach my $xml (@paper_output){
		if ($xml =~ /<LinkSetDb>/){
			$bool = 0;
			next;
		}
		last if ($xml =~ /<\/LinkSetDb>/);
        	next if (!($xml =~ /<Id>(.*)<\/Id>/) || $bool);
        	print $out "\t$1";
	}
	print $out "\n";
	sleep(.4);
}
close($out);
close($gene_file);
