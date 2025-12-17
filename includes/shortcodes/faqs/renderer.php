<?php
/**
 * FAQs Shortcode Renderer
 *
 * Handles rendering of FAQ accordion HTML.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Render FAQ accordion HTML.
 *
 * @since 1.1.0
 * @param array  $faqs Array of FAQ items.
 * @param int    $post_id Post ID.
 * @param string $title FAQ section title.
 * @return string HTML output.
 */
function dynos_render_faqs_accordion($faqs, $post_id, $title = ''): string
{
	// Enqueue accordion scripts and styles using dedicated helper
	\TechmireSolutions\DynamicOnlineServices\Styles\FaqStyles::enqueue(true);

	// Allow filtering the title
	$title = apply_filters('dynos_faqs_title', $title, $post_id);

	ob_start();
	?>
	<div class="service-faqs-accordion" data-post-id="<?php echo esc_attr($post_id); ?>">
		<?php if (!empty($title)): ?>
			<h2 class="faqs-title" style="text-align: center; margin-bottom: 25px;">
				<?php echo esc_html($title); ?>
			</h2>
		<?php endif; ?>
		<?php foreach ($faqs as $index => $faq): ?>
			<?php
			$question = isset($faq['question']) ? trim($faq['question']) : '';
			$answer = isset($faq['answer']) ? trim($faq['answer']) : '';

			if (empty($question)) {
				continue;
			}

			// Allow filtering individual FAQ items
			$faq_item = apply_filters(
				'dynos_faq_item',
				[
					'question' => $question,
					'answer' => $answer,
				],
				$index,
				$post_id
			);

			$question = esc_html($faq_item['question']);
			$answer = wp_kses_post($faq_item['answer']);
			?>
			<div class="faq-item" data-faq-index="<?php echo esc_attr($index); ?>">
				<h3 class="faq-question" id="faq-question-<?php echo esc_attr($index); ?>" role="button" tabindex="0"
					aria-expanded="false" aria-controls="faq-answer-<?php echo esc_attr($index); ?>">
					<span class="faq-question-text"><?php echo esc_html($question); ?></span>
					<span class="faq-icon" aria-hidden="true">+</span>
				</h3>
				<div class="faq-answer" id="faq-answer-<?php echo esc_attr($index); ?>" role="region"
					aria-labelledby="faq-question-<?php echo esc_attr($index); ?>">
					<?php echo wp_kses_post($answer); ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}
