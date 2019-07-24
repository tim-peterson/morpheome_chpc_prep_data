#from pydoc import help
import time
import csv
import sys        
import operator
maxInt = sys.maxsize

while True:
	# decrease the maxInt value by factor 10 
	# as long as the OverflowError occurs.

	try:
		csv.field_size_limit(maxInt)
		break
	except OverflowError:
		maxInt = int(maxInt/10)

with open('/scratch/timrpeterson/MORPHEOME/MeSH_terms.csv') as csv_file:
	csv_reader = csv.reader(csv_file, delimiter=',')

	new_arr = {}
	for row in csv_reader:

		if row[2] not in new_arr:
			new_arr[row[2]] = [row[6]]
		else:
			new_arr[row[2]].append(row[6])

csv_file.close()

with open('/scratch/timrpeterson/MORPHEOME/mesh_gene_paper_count_limited_homologs_top10_with_aliases-desc.csv', 'w') as csvfile:
	spamwriter = csv.writer(csvfile, delimiter=',')
		
	with open('/scratch/timrpeterson/MORPHEOME/mesh_gene_paper_count_limited_homologs_top10-desc.csv') as csv_file:
		csv_reader = csv.reader(csv_file, delimiter=',')

		for row in csv_reader:

			if row[0] in new_arr:

				row0 = row
				
				spamwriter.writerow(row0 + new_arr[row[0]])

	csvfile.close()