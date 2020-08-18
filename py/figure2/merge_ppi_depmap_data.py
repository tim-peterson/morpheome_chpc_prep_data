#from pydoc import help
import scipy
from scipy.stats.stats import pearsonr

from rpy2.robjects.packages import importr
from rpy2.robjects.vectors import FloatVector

stats = importr('stats')

import csv

base_dir = "/Users/timrpeterson/OneDrive-v2/Data/MORPHEOME/FOR_PAPER/Figure2-introduce-morpheome/"

sql_tables = [
	"intersect_HURI_depmap_2020q2_python.csv",
	"intersect_HURI_depmap_2019q4_python.csv",
	"intersect_HURI_depmap_qbf_Avanadata_2018_python.csv",
	"intersect_HURI_depmap_2019q2_python.csv",
	"intersect_HURI_Achilles_RNAi_python.csv", 
	"intersect_bioplex_depmap_2020q2_python.csv", 
	"intersect_bioplex_depmap_2019q4_python.csv",
	"intersect_bioplex_depmap_qbf_Avanadata_2018_python.csv",
	"intersect_bioplex_depmap_2019q2_python.csv",  
	"intersect_bioplex_Achilles_RNAi_python.csv",            
]

ppi_sig = {}

for file in sql_tables:

	with open(base_dir + file) as csv_file:
		csv_reader = csv.reader(csv_file, delimiter=',')

		for row in csv_reader:

			gene_pair = row[0].split('-')
			gene_pair.sort()

			if gene_pair[0] == gene_pair[1]: continue

			gene_pair = gene_pair[0] + '-' + gene_pair[1]

			if gene_pair not in ppi_sig:
				
				ppi_sig[gene_pair] = {'pearsons' : [row[1]], 'pval' : [row[2]]}

			else:

				ppi_sig[gene_pair]['pearsons'].append(row[1])
				ppi_sig[gene_pair]['pval'].append(row[2])
				

output2 = []
for key, value in ppi_sig.items():

	#if len(value["pearsons"])!=len(genes)*len(datasets): continue

	p_adjust = stats.p_adjust(FloatVector(value["pval"]), method = 'BH')
	pval = scipy.stats.stats.combine_pvalues(p_adjust)

	result = (sum(value["pearsons"])/len(value["pearsons"]), pval[1])

	output2.append(list((key,) + result)) 

#sort the output desc
output3 = sorted(output2, key=lambda x: x[1], reverse=True)

with open(base_dir + 'merge_ppi_depmap_data-python.csv', 'w') as csvfile:
	spamwriter = csv.writer(csvfile, delimiter='\t')

	for row in output3:
		spamwriter.writerow(row)

	csvfile.close()