<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php get_header(); ?>

<style>

/*======================
		404 page
=======================*/


.page_404{ 
    padding:40px 0; background:#fff; font-family: 'Arvo', serif;
}

.page_404  img{ width:100%;}

.four_zero_four_bg{
    background-image: url(https://arashjavadi.com/wp-content/uploads/2023/12/arashjavadi.com-caveman-gif-animation-page-404.gif);
    height: 500px;
    background-position: center;
}


.four_zero_four_bg h1{
    font-size:80px;
}

.four_zero_four_bg h3{
    font-size:80px;
}

.link_404{			 
	color: #fff!important;
    padding: 10px 20px;
    background: #39ac31;
    margin: 20px 0;
    display: inline-block;
}
.contant_box_404{ margin-top:-50px;}

</style>


<section class="page_404 ">
	<div class="container">
		<div class="row d-flex justify-content-center ">	
		<div class="col-sm-9 d-flex justify-content-center">
		<div class="col-sm-10 col-sm-offset-1  text-center">
		<div class="four_zero_four_bg">
			<h1 class="text-center ">404</h1>
		
		
		</div>
		
		<div class="contant_box_404">
		<h3 class="h2">
		Look like you're lost
		</h3>
		
		<p>the page you are looking for not avaible!</p>
		
		<a href="<?php echo get_home_url();?>" class="link_404">Go to Home</a>
	</div>
		</div>
		</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>