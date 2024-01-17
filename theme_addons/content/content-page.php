<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Template part for displaying page content.
 *
 */

?>

<?php do_action( 'AJDWP_content_page_top'); ?>


<?php include dirname(__FILE__,2).'/Like_follow/likeFollowCounters.php'; ?>


<article id="post-<?php the_ID(); ?>" <?php post_class('post-items shadow rounded mb-3 '); ?>>

<!-- //------------------------------------Row Date and Author-------------------------------------// -->
<?php if ( !is_page()  ) { ?>

	<div class="row p-4 border-bottom">

		<div class="post-date-author text-start text-uppercase">

			<span class="span-postdate ">Post Date: &nbsp;</span>
			<span class="post-date"> 
				<a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>"><span><?php echo esc_html(get_the_date('j')); ?>/</span><?php echo esc_html(get_the_date("M/Y")); ?></a> 
			</span>

			&nbsp;&nbsp;||&nbsp;&nbsp;

			<span class="span-writtenby">Written By: &nbsp;</span>
			<span class="post-author"> 
				<a href="<?php echo get_author_posts_url (get_the_author_meta('ID'))?>"><?php echo get_the_author(); ?></a> 
			</span>

		</div>

	</div>

<?php } ?>

<!-- //------------------------------------Row Thumbnail and Content-------------------------------------// -->

<div class="row p-4 d-flex align-items-center">

		<?php if ( has_post_thumbnail() ) { ?>

			<div class="post-thumbnail <?php if ( is_single() || is_page() ) {echo "col-lg-12 py-3";}else{echo"col-lg-3";}?> ">

				<?php if ( !is_page() ) { ?>
			
					<div class="featured-image d-flex align-items-center justify-content-center">
						<a href="<?php echo esc_url(get_permalink()); ?>" class="post-hover">
							<?php 
							if ( is_single() ) {
								the_post_thumbnail('medium_large'); 
							}
							else{
								the_post_thumbnail('thumbnail'); 
							}
							
							?>
						</a>
					</div>
	
				<?php } ?>
			</div>
		<?php } ?>
			


		<div class="post-content-exerpt 

				<?php 	if ( is_single() || is_page() ) 
							{echo "col-lg-12";}
						elseif(has_post_thumbnail())
							{echo"col-lg-9";}
						else{{echo"col-lg-12";}
						}
				?>
				
				">
			
			<?php if ( is_single() || is_page() ) : ?>

				<div class="text-uppercase h2 AJDWP_page_title">
				<?php the_title(); ?> 
				</div>

				<div class="post-content text-justify">
					<?php the_content(); ?>
				</div>

			<?php  //elseif ( !is_single() ) : the_title( sprintf( '<h3 class="post-title "><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?> 
			<?php  elseif ( !is_single() ) : ?>

				<div class="text-uppercase h2">
					<a href="<?php echo esc_url( get_permalink() );?>"><?php the_title(); ?> </a>
				</div>

				
		
			<div class="post-excerpt text-justify">

				<?php the_excerpt(); ?>		

			</div>
			
			<?php endif;?>

		</div>

</div>

<!-- //------------------------------------Row Like, Follow , views and Social Medias-------------------------------------// -->

<div class="row border-top py-4 d-flex align-items-center ">

	<div class="col-8 justify-content-start d-inline-flex">
		<?php if(!is_page()){ ?>
			<div class="like_buttons   d-flex justify-content-start align-items-center">
				<?php if(is_user_logged_in()){ ?>
						<?php do_action( 'AJDWP_like_follow_social' ); ?>
				<?php }else{ 	
						echo "Total likes: " . $totalLikes;  
				}?>
				
			</div>
		<?php } ?>

	<?php if(!is_page()){ ?>	
		<div class="d-flex align-items-center p-1 mx-4 border border-1 rounded d-inline-flex position-relative <?php if(!empty($follow_exsists)){echo "border-primary";}?>" style="width:max-content;">
			<a href="<?php echo get_author_posts_url(get_the_author_meta('ID'));?>">
				<div class="g-0 d-flex justify-content-start align-items-center">
					<div class="">
						<?php
							$like_follow_custom_avatar_url = get_user_meta(get_the_author_meta('ID'), 'custom_avatar_url', true);

							if (!empty($like_follow_custom_avatar_url)) {
								echo '<img src="' . esc_url($like_follow_custom_avatar_url) . '" alt="Avatar" class="img-fluid rounded" style="width:50px; height:50px;">';
							} else {
								// If custom avatar URL is not set, fall back to the default avatar display
								echo get_avatar(get_the_author_meta('ID'), $size='50px', null, null, array('class' => array('img-fluid rounded') )); 
							}							
						?>
					</div>
					<div class="px-2 <?php if(!empty($follow_exsists)){echo "text-primary";}?>">
						<span class=""><?php echo get_userdata(get_the_author_meta('ID'))->display_name; ?></span>
					</div>
					<div class="post_page_author_total_follow position-absolute top-0 start-100 translate-middle badge rounded-pill <?php if(!empty($follow_exsists)){echo "bg-primary";}else{echo"bg-secondary";}?>">
						<?php 
							echo $totalfollow;
						?>
					</div>
				</div>
			</a>
		</div>
	<?php } ?>

	<?php if(is_page()){ ?>
		<div class="col-8">

			<div class="post-date-author text-start text-uppercase">

				<span class="span-postdate ">Created Date: &nbsp;</span>
				<span class="post-date"> 
					<a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>"><span><?php echo esc_html(get_the_date('j ')); ?></span><?php echo esc_html(get_the_date('M, Y')); ?></a> 
				</span>

			</div>

		</div>
	<?php } ?>


	</div>

	<div class="col-4 text-end">
		<?php 

		if(is_single() || is_page()){
			setPostViews(get_the_ID());
		}

		echo getPostViews(get_the_ID());
		?>
	</div>

</div>




</article>




<?php do_action( 'AJDWP_content_page_bottom') ?>