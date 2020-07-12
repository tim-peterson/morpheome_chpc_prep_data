# morpheome_chpc_prep_data


### Order of scripts to run to generate data that is uploaded to Algolia for use on morpheome website

1. Nick - download all PMIDs for each [MeSH term] and [gene]. These we colloquially call "MeSH paper" and "gene paper" tables.
2. Nick - intersect MeSH paper and gene paper tables to get "MeSH gene" table, which has co-citation count of MeSH terms and gene.
3. Nick - Intersect gene paper table to get "gene gene" table, which has co-citation count of each gene with all other genes.
4. CHPC (Tim) - Split ["MeSH gene" and "gene gene" tables](https://github.com/tim-peterson/morpheome_chpc_prep_data/blob/master/bash/split_big_data_into_rows_of_1000.sh) into files with 1000 rows to make manageable. Header row is maintained on each file.
5. CHPC (Tim) - Get Get top 5 genes for each MeSH term using [step2_get_top5_mesh_gene_intersect_mesh_counts_2020.php](https://github.com/tim-peterson/morpheome_chpc_prep_data/blob/master/php/step2_get_top5_mesh_gene_intersect_mesh_counts_2020.php).
6. CHPC (Tim) - Get top 1 co-cited gene for each top 5 gene [get_top5_cited_genes_for_top5_mesh_genes.php](https://github.com/tim-peterson/morpheome_chpc_prep_data/blob/master/php/get_top5_cited_genes_for_top5_mesh_genes.php). Uses gene_gene split files.
7. CHPC (Tim) - Generate list of Bioplex PPIs that have no citations using [bioplex_get_zero_gene_gene_cocitations.php](https://github.com/tim-peterson/morpheome_chpc_prep_data/blob/master/php/bioplex_get_zero_gene_gene_cocitations.php) Uses gene_gene split files.
8. Macbook (Tim) -  Intersect top MeSH genes and its co-cited gene with Bioplex no citation list using [ppi_no_citations_mesh_intersect.php](https://github.com/tim-peterson/morpheome/blob/master/app/Console/Commands/Morpheome/pipeline/ppi_no_citations_mesh_intersect.php)
9. Macbook (Tim) - Generate list of normalized Z-scores for each gene from each drug screen [top_hits_small_mol_screens_with_external_morpheome.php](https://github.com/tim-peterson/morpheome/blob/master/app/Console/Commands/Morpheome/pipeline/top_hits_small_mol_screens_with_external_morpheome.php) 
10. Macbook (Tim) - Intersect genes-ppi with crispr drug screens using [ppi_mesh_drug_screens_intersect.php](https://github.com/tim-peterson/morpheome/blob/master/app/Console/Commands/Morpheome/pipeline/ppi_mesh_drug_screens_intersect.php)
11. CHPC (Tim) - Generate individual gene co-essentiality scores using [coessentiality_all_genes.py](https://github.com/tim-peterson/morpheome_chpc_prep_data/blob/master/py/coessentiality_all_genes.py)
12. CHPC (Tim) - Generate short list of top depmap co-essentiality scores using [depmap_2019q4_get_top_e-5.php](https://github.com/tim-peterson/morpheome_chpc_prep_data/blob/master/php/depmap_2019q4_get_top_e-5.php). Used .00001 as p-value cut-off. 
13. Macbook (Tim) - Intersect genes-ppi-drug with depmap using [ppi_mesh_depmap_screens_intersect.php](https://github.com/tim-peterson/morpheome/blob/master/app/Console/Commands/Morpheome/pipeline/ppi_mesh_depmap_screens_intersect.php)
