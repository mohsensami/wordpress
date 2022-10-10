$terms = get_the_terms(get_the_ID(),'product_cat');

//Get an array of their IDs
$term_ids = wp_list_pluck($terms,'term_id');

//Get array of parents - 0 is not a parent
$parents = array_filter(wp_list_pluck($terms,'parent'));

//Get array of IDs of terms which are not parents.
$term_ids_not_parents = array_diff($term_ids,  $parents);

//Get corresponding term objects
$terms_not_parents = array_intersect_key($terms,  $term_ids_not_parents);

//var_dump($term_ids_not_parents);
