import csv

dataset = "/scratch/njacobs/mesh_gene_intersect.tsv"


gene_mesh_terms = {};

with open(dataset) as csv_file:
	csv_reader = csv.reader(csv_file, delimiter="\t")

		for row in csv_reader: