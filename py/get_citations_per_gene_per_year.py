
from time import sleep
import csv
from datetime import datetime
from Bio import Entrez # install with 'pip install biopython'
from Bio.Entrez import esummary, read
Entrez.email = "timrpeterson@wustl.edu" # register your email


base_path = "/home/jiwpark00/timrpeterson/Figure1-pubmed/"

year = datetime.today().year
years = list(range(year, year - 31, -1))
years.sort(reverse=False)

len_years = len(years)

pmids = {}

csvfile = base_path + "gene_paper_count_lt10_per_paper.txt"

input_genes = []

with open(csvfile) as fin:
	csv_reader = csv.reader(fin, delimiter="\t")

	for row in csv_reader:

		if int(row[1]) < 25: continue

		input_genes.append(row[0])
#input_genes = ["P53", "DEPTOR", "ATRAID", "LRP5", "SOST", "C9orf72", "SOD1", "EML4", "MAVS", "P53", "MTOR", "FDPS", "NELL1"]

#input_genes = ["TP53","TNF","APOE","IL6","TGFB1","VEGFA","EGFR","TLR4","IL10","IL1B","COMT","BRCA2","SERPINE1","TNFRSF1A","CDKN1B","INS","RAC1","DMD","MAPK8","SPP1","EPHA2","PKD2","CCK","EPCAM","GNB3","HSPA9","VCL","ADRA2A","FGG","GPER1","SCAMP1","SLC2A5","SMARCAD1","SMU1","SNRPC","STT3B","STX16","SUPT3H","TAOK1","TCAP","IFI6","ISY1","KCNK6","KCTD20","KLK13","LAMTOR4","LCMT2","LENG1","LGALSL","LIME1","TNDM","TPSD1","TRAPPC3L","TRBC1","TTC24","TUBB7P","UGT2B11","VNN3","WDR87","XAGE2","SNX18P21","SNX18P22","SOD1P3","SPDYE20P","SPG23","SPG3B","SPG45","SPG56","SPHAR","SRXY10"]


for k, gene in enumerate(input_genes):

	#if k % 2 == 0: continue
		
	#pmids[gene] = []

	for key, val in enumerate(years):

		if (key + 1) == len_years:
			break
			
		#(("2001"[Date - Completion] : "2020"[Date - Completion])) AND (mtor[Title/Abstract]) 

		handle = Entrez.esearch(db='pubmed', term="((" + str(val) + "[Date - Publication] : "+ str(years[key + 1]) +"[Date - Publication])) AND (" + gene + "[Title/Abstract]) "
	, retmax=100, retmode='xml')
		records = Entrez.read(handle)

		#pmids[gene].append([val, records['Count']])

		csvfile_new = base_path + "get_citations_per_gene_per_year.txt"

		with open(csvfile_new, 'a+') as fout:

			writer = csv.writer(fout, delimiter='\t')

			writer.writerow([gene, val, records['Count']])

		sleep(0.35)

	#if k > 3: break



