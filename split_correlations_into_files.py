#from pydoc import help
import time
import csv
import sys

#print('iiiiii')
# /scratch/timrpeterson/MORPHEOME/DepMap/DepMap_pearsons_2019q1_gene_names_only.csv

line_count = 0
# '/scratch/timrpeterson/MORPHEOME/DepMap/depmap_2018q4_pearsons.csv'
with open('/scratch/timrpeterson/MORPHEOME/all_crispria_pearsons.csv') as csv_file:
	csv_reader = csv.reader(csv_file, delimiter=',')
	
	next(csv_file)
	input_genes = []
	gene = "A1BG"
	for row in csv_reader:

		x = row[0]

		if x != gene:

			#path = "/scratch/timrpeterson/MORPHEOME/"
			with open('/scratch/timrpeterson/MORPHEOME/interaction_correlations_basal-crispria/' + gene + '-pearsons_crispria.csv', 'w') as csvfile:

			#with open('/scratch/timrpeterson/MORPHEOME/interaction_correlations_basal/2018q4/' + gene + '-DepMap_pearsons_2018q4.csv', 'w') as csvfile:
				spamwriter = csv.writer(csvfile, delimiter=',')

				for row0 in input_genes:
					#if row[2] < .00000001:
					#if any(field.strip() for field in row):
					spamwriter.writerow(row0)

				csvfile.close()

			gene = row[0]
			input_genes = []
		#if line_count == 0:
		input_genes.append(row)
		#input_genes_str = '_'.join(input_genes)


