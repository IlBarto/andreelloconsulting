<?php
/*
Template Name: Contatti
*/

function my_contact_form_generate_response($type, $message){
	global $response, $human_trial, $human_trials;
	$response = "<div id='response' class='alert alert-{$type} hide' role='alert'>{$message}</div>";
	$human_trial = $human_trials[rand(1,9)];
}

$response = "";

$human_trials = array(
        '',//null
        ' + 2 = 3',//1
        ' + 4 = 6',//2
        ' - 1 = 2',//3
        ' + 3 = 7',//4
        ' + 5 = 10',//5
        ' - 2 = 4',//6
        ' - 6 = 1',//7
        ' + 1 = 9',//8
        ' - 5 = 4',//9
);

$human_trial = $_POST['human_trial'];

//user posted variables
$name = $_POST['message_name'];
$email = $_POST['message_email'];
$message = $_POST['message_text'];
$human = $_POST['message_human'];
$newsletter = $_POST['message_newsletter'];

//php mailer variables
$to = get_theme_mod('andreello_contact_form_mail');
$from = get_theme_mod('andreello_message_from_address');
$subject = "Qualcuno ha mandato un messaggio da ".get_bloginfo('name');
$headers = "From: {$from}\r\n" .
           'Reply-To: ' . $email . "\r\n";

//response messages
$not_human       = esc_html( translate('Controllo AntiBot errato','imelab') );
$missing_content = esc_html( translate('Per favore compila tutti i campi','imelab') );
$email_invalid   = esc_html( translate('Inserisci un indirizzo e-mail valido','imelab') );
$message_unsent  = esc_html( translate("Invio del messaggio fallito. In caso di molteplici errori, contatta l'ammistratore del sito","imelab") );
$message_sent    = esc_html( translate('Grazie! Il tuo messaggio è stato inviato','imelab') );

if(!$human) {
	if ($_POST['submitted']) {
		my_contact_form_generate_response("danger", $missing_content);
	} else {
	   $human_trial = $human_trials[rand(1,9)];
    }
} else {
	if($human != array_search($human_trial, $human_trials)) {
		my_contact_form_generate_response( "danger", $not_human );
	} else {
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			my_contact_form_generate_response( "danger", $email_invalid );
		} else {
			if(empty($name) || empty($message)){
				my_contact_form_generate_response("danger", $missing_content);
			} else {
				$new_message = "Nome: {$name}\nMessaggio: {$message}\n";

				if(!is_null($newsletter)) {
					$new_message .= "\nVuole iscriversi alla newsletter";
				}

				if(wp_mail($to, $subject, strip_tags($new_message), $headers)) {
					my_contact_form_generate_response( "success", $message_sent );
				} else {
					my_contact_form_generate_response("danger", $message_unsent);
				}
			}
		}
	}
}

get_header(); ?>

	<div id="primary" class="fp-content-area">
		<main id="main" class="site-main" role="main">

			<div class="entry-content">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>

					<div>
						<?php echo $response; ?>
						<form action="<?php the_permalink(); ?>" method="post">
							<div class="form-group">
								<label for="message_name"><?php esc_html_e('Nome', 'andreello') ?></label>
								<input id="message_name" type="text" name="message_name" class="form-control" value="<?php echo esc_attr($_POST['message_name']); ?>">
							</div>
							<div class="form-group">
								<label for="message_email"><?php esc_html_e('E-mail', 'andreello') ?></label>
								<input id="message_email" type="email" class="form-control" name="message_email" value="<?php echo esc_attr($_POST['message_email']); ?>">
							</div>
							<div class="form-group">
								<label for="message_text"><?php esc_html_e('Messaggio', 'andreello') ?></label>
								<textarea id="message_text" class="form-control" rows="3" name="message_text"><?php echo esc_textarea($_POST['message_text']); ?></textarea>
							</div>
							<div class="form-group form-check">
								<input id="message_newsletter" type="checkbox" class="form-check-input" name="message_newsletter" value="<?php echo esc_attr($_POST['message_newsletter']); ?>">
								<label class="form-check-label" for="message_newsletter"><?php esc_html_e('Newsletter', 'andreello') ?></label>
							</div>
							<div class="form-group">
								<label for="message_human" style="display: block;"><?php esc_html_e('Controllo anti-bot', 'andreello') ?></label>
								<input id="message_human" type="text" class="form-control" style="display: inline-block; width: 60px;" name="message_human"><?php echo $human_trial ?>
							</div>
							<input type="hidden" name="human_trial" value="<?php echo esc_attr($human_trial) ?>">
							<input type="hidden" name="submitted" value="1">
							<button type="submit" class="btn btn-secondary"><?php esc_html_e('Invia', 'andreello') ?></button>
						</form>
					</div>

				<?php endwhile; ?>
			</div><!-- .entry-content -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
