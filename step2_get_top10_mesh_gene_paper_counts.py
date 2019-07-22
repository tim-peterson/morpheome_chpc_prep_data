#from pydoc import help
import time
import csv
import sys        

maxInt = sys.maxsize

while True:
	# decrease the maxInt value by factor 10 
	# as long as the OverflowError occurs.

	try:
		csv.field_size_limit(maxInt)
		break
	except OverflowError:
		maxInt = int(maxInt/10)

with open('/scratch/timrpeterson/mesh_gene_paper_count_limited_unfiltered_top10.csv', 'w') as csvfile:
	spamwriter = csv.writer(csvfile, delimiter=',')
		
	with open('/scratch/njacobs/mesh_gene_intersect_limited_homologs.tsv') as csv_file:
		csv_reader = csv.reader(csv_file, delimiter='\t')
		
		#next(csv_file)

		#input_genes = []
		line_count = 0
		for row in csv_reader:

			row0 = row 
			mesh_name = row[0]

			line_count +=1

			if line_count == 1:
				
				col_names = row0
			
				continue
		
			#del row0[0]

			row0.sort()
			
			top10 = row0[:10]

			new_arr = [mesh_name.replace('"', '')]

			for k, v in enumerate(top10):

				new_arr.append(col_names[k])
				new_arr.append(v)                               

			spamwriter.writerow(new_arr)

	csvfile.close()