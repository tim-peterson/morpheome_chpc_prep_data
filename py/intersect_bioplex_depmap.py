#from pydoc import help
import scipy
from scipy.stats.stats import pearsonr

from rpy2.robjects.packages import importr
from rpy2.robjects.vectors import FloatVector

stats = importr('stats')


import csv
#import sys
#import pandas as pd
#import numpy as np


#path = "/Users/timrpeterson/OneDrive - Washington University in St. Louis/Data/MORPHEOME/"

output_path = "/home/jiwpark00/timrpeterson/MORPHEOME/FOR_PAPER/Figure2-introduce-morpheome/";


base_dir = "/home/jiwpark00/timrpeterson/njacobs/bioplex/"

sql_tables = [
    base_dir + "bioplex_v1_293T_minimum_info.csv",
    base_dir + "bioplex_v2_293T_minimum_info.csv",
    base_dir + "bioplex_v3_293T_minimum_info.csv",
    base_dir + "bioplex_v3_HCT116_minimum_info.csv"

]

#sql_tables = [output_path + 'HURI_ppi_convert_2_gene_symbol.csv']
#print('supss')

ppi = {}

for y in sql_tables:

	with open(y) as csv_file:
		csv_reader = csv.reader(csv_file, delimiter=',')
		next(csv_reader)

		cnt = 0
		for row in csv_reader:

			if row[0] not in ppi:
				ppi[row[0]] = {row[1]: row[2]}

			else:
				ppi[row[0]][row[1]] = row[2]

'''			if cnt > 100:
				break
			cnt+=1'''


'''			if row[0] not in ppi:
				ppi[row[0]] = {row[1]: row[1]}

			else:
				ppi[row[0]][row[1]] = row[1]			'''


depmap_file = 'broad_2019q2_t.csv'
depmap_file = "qbf_Avanadata_2018.csv"
depmap_file = 'Achilles_gene_effect-2019q4-Broad_t_noNAs.csv'	
depmap_file = 'D2_Achilles_gene_dep_scores.csv'
depmap_file = 'depmap_2020q2_t.csv'

with open(output_path + depmap_file) as csv_file:
	csv_reader = csv.reader(csv_file, delimiter=',')
	next(csv_reader)

	genes = {}
	for row in csv_reader:

		gene = row[0]
		#gene = gene.split("..")
		gene = gene.split(" ")
		
		row.pop(0)

		genes[gene[0]] = row
		#genes[gene] = row

output = {}

for key, val in ppi.items():

	if key not in genes: continue

	target_NAs = [i for i, a in enumerate(genes[key]) if a == "NA" or a == '']

	for v, ppi_score in val.items():

		if v not in genes: continue

		dest_NAs = [i for i, a in enumerate(genes[v]) if a == "NA" or a == '']

		indices_to_remove = list(set(target_NAs + dest_NAs))

		filtered_value = [i for j, i in enumerate(genes[v]) if j not in indices_to_remove] 
		filtered_gene = [i for j, i in enumerate(genes[key]) if j not in indices_to_remove]

		if len(filtered_value) < 2 or len(filtered_gene) < 2: continue

		result = pearsonr([float(elt) for elt in filtered_value], [float(elt) for elt in filtered_gene])

		if key in output:

			output[key+"-"+v]["pearsons"].append(result[0])

			output[key+"-"+v]["ppi_score"] = ppi_score

			if "pval" in output[key+"-"+v] and result[1]!=0:

				output[key+"-"+v]["pval"].append(result[1])

			elif "pval" not in output[key] and result[1]!=0: 

				output[key+"-"+v]["pval"] = [result[1]]
		else:

			if result[1]!=0: 

				output[key+"-"+v] = {"pearsons" : [result[0]], "pval" : [result[1]], "ppi_score" : ppi_score }
			else:
				output[key+"-"+v] = {"pearsons" : [result[0]], "pval" : [0], "ppi_score" : ppi_score}



output2 = []
for key, value in output.items():

	#if len(value["pearsons"])!=len(genes)*len(datasets): continue

	p_adjust = stats.p_adjust(FloatVector(value["pval"]), method = 'BH')
	pval = scipy.stats.stats.combine_pvalues(p_adjust)

	result = (sum(value["pearsons"])/len(value["pearsons"]), pval[1], value["ppi_score"])

	output2.append(list((key,) + result)) 

#sort the output desc
output3 = sorted(output2, key=lambda x: x[1], reverse=True)



with open(output_path + 'intersect_bioplex_depmap_2020q2_python.csv', 'w') as csvfile:
	spamwriter = csv.writer(csvfile, delimiter=',')

	for row in output3:
		#if any(field.strip() for field in row):
		spamwriter.writerow(row)

	csvfile.close()

