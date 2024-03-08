<?php
/**
* Template Name: List-Taxonomy
*/
get_header(); ?>
<main id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
	<section>	
			<table id="tabletaxonomy" class="compact stripe hover row-border" style="width:100%">
				<thead>
					<tr>
					  <th>Taxonomy</th>
					  <th>Total post</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
<script>
jQuery(document).ready(function($) {
 
  var jobtable = $('#tabletaxonomy').DataTable({
    processing: true,
    serverSide: true,
	  bInfo : false,
    ajax: {
			  type: "POST",
              dataType: "json",
              url: "/wp-admin/admin-ajax.php", //this is wordpress ajax file which is already avaiable in wordpress
              data: {
                    action:'list_taxonomy'
                },
			},
	lengthMenu: [[25], [25]],
			fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                $('td:eq(0)', nRow).html( '<a href="'+ aData[2] +'" style="color: #066cbd">'+aData[0]+'</a>' );
            }
  });
});
</script>	
</main>
<?php get_footer(); ?>
