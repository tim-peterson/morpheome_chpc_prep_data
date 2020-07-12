# split mesh_gene file
tail -n +2 mesh_gene_intersect.tsv | split -l 1000 - split_
for file in split_*
do
    head -n 1 mesh_gene_intersect.tsv > tmp_file
    cat $file >> tmp_file
    mv -f tmp_file $file
done

# split gene_gene file
tail -n +2 gene_gene_intersect.tsv | split -l 1000 - split_gene_gene_
for file in split_gene_gene_*
do
    head -n 1 gene_gene_intersect.tsv > tmp_file
    cat $file >> tmp_file
    mv -f tmp_file $file
done
