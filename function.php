/* Datatable */
function add_datatables(){
  wp_enqueue_script('datatables', '//cdn.datatables.net/2.0.2/js/dataTables.min.js', array('jquery') );
  wp_enqueue_style('datatables_style', '//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css');
}
add_action('wp_enqueue_scripts', 'add_datatables');

function my_ajax_gettaxonomy() {
  header("Content-Type: application/json");
  $request= $_POST;
  $taxonomy=  $request['taxonomy'];
  $columns = array(
    0 => 'post_title',
    1 => 'count'
  );
  
  $args = array(
    'taxonomy'      => array( $taxonomy ),
    'number' => $request['length'],
    'show_count' => true,
    'offset' => $request['start'],
    'order' => $request['order'][0]['dir'],
    ); 

  if ($request['order'][0]['column'] == 0) {
    $args['orderby'] = $columns[$request['order'][0]['column']];
  }elseif ($request['order'][0]['column'] == 1) {

    $args['orderby'] = 'count';
  }
  //$request['search']['value'] <= Value from search

  if( !empty($request['search']['value']) ) { // When datatables search is used
    $args['name__like'] = sanitize_text_field($request['search']['value']);
  }

  $terms = get_terms( $args );
  if( !empty($request['search']['value']) ) {
    $totalData=sizeof ($terms);
  }else{
     $totalData   = wp_count_terms( $taxonomy, array( 'hide_empty' => TRUE ) ); 
  }
  if ($terms) {
      foreach($terms as $term){
        $nestedData = array();
        $nestedData[] = $term->name;
        $nestedData[] = $term->count;
        $nestedData[] = home_url($term->taxonomy . '/' . $term->slug);
        $data[] = $nestedData;
      }
      $json_data = array(
          "draw" => intval($request['draw']),
          "recordsTotal" => intval($totalData),
          "recordsFiltered" => intval($totalData),
          "data" => $data
        );
        echo json_encode($json_data);
      } else {
    
        $json_data = array(
          "data" => array()
        );
        echo json_encode($json_data);
  }
  wp_die();
}
add_action('wp_ajax_get_taxonomy', 'my_ajax_gettaxonomy');
add_action('wp_ajax_nopriv_get_taxonomy', 'my_ajax_gettaxonomy');
