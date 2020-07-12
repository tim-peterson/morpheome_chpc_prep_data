tail -n +2 mesh_gene_intersect.tsv | split -l 1000 - split_
for file in split_*
do
    head -n 1 mesh_gene_intersect.tsv > tmp_file
    cat $file >> tmp_file
    mv -f tmp_file $file
done
