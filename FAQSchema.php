<?php
/**
 * Plugin Name: Faq Schema
 * Plugin URI: https://www.anastasys.gr
 * Description: Display FAQ content and Script shema using a shortcode to insert in a page or post [anastasys-schema question=" | " answer=" | "]
 * Version: 0.1
 * Text Domain: anastasys.gr
 * Author: Anastasios Tsourounis
 * Author URI: https://www.anastasys.gr
 */
//Receive an array of texts and display it

function enqueue_related_pages_scripts_and_styles(){
	wp_enqueue_style('faq-styles', plugins_url('assets/css/faq.css', __FILE__));
	wp_enqueue_script('faq-script', plugins_url( 'assets/js/faq.js' , __FILE__ ),false,1.0,true);
}

add_action('wp_enqueue_scripts','enqueue_related_pages_scripts_and_styles');

function faqSchema($atts){
	ob_start();
    $attributes = shortcode_atts(
        array(
           'question' => '',
		   'answer' => ''
         ), 
        $atts
    );

    // Create our array of values
    // First, sanitize the data and remove white spaces

    $no_whitespaces_text = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['question'], FILTER_SANITIZE_FULL_SPECIAL_CHARS ) ); 
    $question_array = explode( '|', $no_whitespaces_text );
	
	$no_whitespaces_text = preg_replace( '/\s*,\s*/', ',', filter_var( $attributes['answer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ); 
    $answer_array = explode( '|', $no_whitespaces_text ); ?>

	<div itemscope itemtype="https://schema.org/FAQPage" class="accordion">
		<h2 class="accordion__heading">Frequently Asked Questions</h2>
    <?php foreach(array_keys($question_array) as $key) { ?>
		<div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" class="accordion__item">
			<button class="accordion__btn">
				<span itemprop="name" class="accordion__caption"><i class="far fa-lightbulb"></i><?php echo html_entity_decode($question_array[$key]) ?></span>
				<span class="accordion__icon"><i class="fa fa-plus"></i></span>
			</button>

			<div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer" class="accordion__content">
			<p itemprop="text"><?php echo html_entity_decode($answer_array[$key]) ?></p>
			</div>
		</div>
	<?php } ?>
	</div>
<?php
$myvariable = ob_get_clean();
return $myvariable;
}
add_shortcode('forthright-schema', 'faqSchema');

// [extraServices question='Microneedling, Needle Roller, Skin Boosters' answer='1,2,3']