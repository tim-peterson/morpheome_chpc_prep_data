

#from pydoc import help
from scipy.stats.stats import pearsonr
#help(pearsonr)

import csv

import numpy as np
import pandas as pd


input = ["ATRAID (51374)"]

input = ["SLC37A3 (84255)"]
input = ["FDPS (2224)"]
input = ["MTOR (2475)"]
input = ["TGFBR2 (7048)"]
input = ["TGFBR1 (7046)"]
input = ["FBN1"]
input = ["SMAD3"]
input = ["TGFBR2"]
input = ["MTOR..2475."]
input = ["MTOR..2475."]
input = ["ATRAID..51374."]
input = ["ATRAID..51374."]

input = ["FDPS..2224."]
input = ["HMGCR..3156."]
input = ["SIGMAR1..10280."]
input = ["TMEM97..27346."]

input = ["FDPS"]
input = ["HMGCR"]
input = ["SPTLC2"]
input = ["SPTLC2..9517."]
input = ["COL4A3BP..10087."]
input = ["IFT81..28981."]
input = ["ZZZ3..26009."]
input = ["UBALD1..124402."]

input0 = input[0]
#input = ["UBALD2..283991."]


'''with open('/Users/timrpeterson/Downloads/gene_effect_corrected_output.csv') as csv_file:
	csv_reader = csv.reader(csv_file, delimiter=',')
	next(csv_reader)

quit()	'''
base_path = '/home/jiwpark00/timrpeterson/njacobs/'



#dataset = '/Users/timrpeterson/OneDrive - Washington University in St. Louis/Data/MORPHEOME/DepMap/gene_effect_corrected_output.csv'
#dataset = '/Users/timrpeterson/OneDrive - Washington University in St. Louis/Data/MORPHEOME/Hart-Moffat/qbf_Avanadata_2018.txt'
dataset = base_path + 'Achilles_gene_effect-2019q4-Broad_t.csv'
dataset = base_path + 'Achilles_gene_effect-2019q4-Broad_t_noNAs.csv'
#dataset = base_path + 'gene_effect_corrected_t_clean_gene_name.csv'
#dataset = base_path + 'qbf_Avanadata_2018.txt'

if "gene_effect" not in dataset:
	age = '2018q4'
	delimiter = '\t'
	remove_gene_id = False 
else:
	if "2019q4" in dataset:
		age = '2019q4'
	elif "2018" in dataset:
		age = '2018'		
	else: 	
		age = '2019q1'
	delimiter = ','
	remove_gene_id = True 

input_genes = []

with open(dataset) as csv_file:
	csv_reader = csv.reader(csv_file, delimiter=delimiter)
	next(csv_reader)

	for row in csv_reader:
		
		input_genes.append(row[0])

	csv_file.close()

cnt = 0
for input0 in input_genes:

	if cnt < 17204:
		cnt +=1
		continue

	input1 = input0.split("..")

	#print(input1)

	with open(dataset) as csv_file:
		csv_reader = csv.reader(csv_file, delimiter=delimiter)
		next(csv_reader)

		with open(base_path + 'interactions_correlation_basal/' + input1[0] + '-pearsons-python-' + age + '.csv', 'w') as csvfile:

		#with open('/Users/timrpeterson/OneDrive - Washington University in St. Louis/Data/MORPHEOME/DepMap/cherry-picked/' + input0 + '-pearsons-python-' + age + '.csv', 'wb') as csvfile:
			spamwriter = csv.writer(csvfile, delimiter=',')
		
			genes = {}
			for row in csv_reader:
				gene = row[0]
				row.pop(0)

				if remove_gene_id is True:
					arr = gene.split("..")
				#row_temp.pop(0)
					genes[arr[0]] = row
				else:
					genes[gene] = row
			#for k, v in genes.iteritems():
				#print k, v
				#quit()
			output = []

			# Build list of NA inside target gene
			target_NAs = [i for i, x in enumerate(genes[input1[0]]) if x == "NA" or x == '']

			for key, value in genes.items(): 
				'''x, y = np.array(value), np.array(genes[input1[0]])
				nas = np.logical_or(pd.isnull(x), pd.isnull(y))
				result = pearsonr(x[~nas], y[~nas])'''
				dest_NAs = [i for i, x in enumerate(value) if x == "NA" or x == '']

				indices_to_remove = list(set(target_NAs + dest_NAs))

				filtered_value = [i for j, i in enumerate(value) if j not in indices_to_remove] 
				filtered_gene = [i for j, i in enumerate(genes[input1[0]]) if j not in indices_to_remove]
				#all_targets = target_NAs + dest_NAs

				#print(filtered_gene)
				#print(filtered_value)
				result = pearsonr([float(elt) for elt in filtered_value], [float(elt) for elt in filtered_gene])

				#result = pearsonr(np.array(filtered_value).astype(np.float), np.array(filtered_gene).astype(np.float))

				#result = pearsonr(np.array(value).astype(np.float), np.array(genes[input1[0]]).astype(np.float))

				output.append(list((key,) + result)) 

				#sort the output desc
			output2 = sorted(output, key=lambda x: x[1], reverse=True)

			spamwriter.writerows(output2)
	#		for row in output2:		
				#if any(field.strip() for field in row):
	#			print(row)
				#spamwriter.writerow(row)

			csvfile.close()

