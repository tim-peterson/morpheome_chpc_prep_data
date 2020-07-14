from collections import defaultdict

#File containing genes and their co-cited papers. <Rename appropriately>
gene_file = open('/scratch/njacobs/gene_papers.tsv', 'r')

#Organize into a dictionary where genes are the keys and the values are lists of co-cited papers
gene_dict = defaultdict(list)
paper_dict = {}
line = gene_file.readline()
while line:
    index = line.split("\t")
    if len(index) > 1:
        gene_dict[index[0]] = set(index[1:])
        for paper in index[1:]:
            if not paper in paper_dict:
                paper_dict[paper] = 1
            else:
                paper_dict[paper] += 1
    line = gene_file.readline()
gene_dict_keys = sorted(gene_dict.keys())

#Define a set of papers that probably don't relate to specific genes.
#Default threshold of >= 10 genes per paper. <Adjustable>
survey_papers = set(i for i,j in paper_dict.items() if j >= 10)

#Output file name. <Rename Appropriately>
out = open('/scratch/njacobs/mesh_gene_intersect.tsv', 'w')

#Write column names
out.write(gene_dict_keys[0])
for key in gene_dict_keys[1:]:
    out.write("\t" + key)
out.write("\n")
gene_file.close()

#File containing MeSH terms and their co-cited papers. <Rename appropriately>
mesh_file = open('/scratch/njacobs/mesh_papers.tsv', 'r')

#Intersects MeSH term papers with gene co-citations and writes number of intersections to output
line = mesh_file.readline()
mesh_terms = {}
while line:
    index = line.split("\t")
    if len(index) > 1 and not index[0] in mesh_terms:
        out.write(index[0])
        mesh_set = set(index[1:])
        for key in gene_dict_keys:
            out.write("\t" + str(len(gene_dict[key].intersection(mesh_set).difference(survey_papers))))
        out.write("\n")
        mesh_terms[index[0]] = 1
    line = mesh_file.readline()
out.close()
mesh_file.close()
