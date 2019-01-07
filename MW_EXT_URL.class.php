<?php

namespace MediaWiki\Extension\MetaStore;

use OutputPage, Parser, Skin;

/**
 * Class MW_EXT_URL
 */
class MW_EXT_URL {

	/**
	 * * Clear URL.
	 *
	 * @param $string
	 *
	 * @return string
	 */
	private static function clearURL( $string ) {
		$outString = rawurlencode( trim( $string ) );

		return $outString;
	}

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setFunctionHook( 'url', [ __CLASS__, 'onRenderTag' ] );

		return true;
	}

	/**
	 * Render `URL` function.
	 *
	 * @param Parser $parser
	 * @param string $type
	 * @param string $content
	 * @param string $title
	 *
	 * @return bool|string
	 */
	public static function onRenderTag( Parser $parser, $type = '', $content = '', $title = '' ) {
		// Argument: type.
		$getType = MW_EXT_Kernel::outClear( $type ?? '' ?: '' );

		// Argument: content.
		$getContent = MW_EXT_Kernel::outClear( $content ?? '' ?: '' );

		// Argument: title.
		$getTitle = MW_EXT_Kernel::outClear( $title ?? '' ?: '' );

		// Build URL.
		switch ( $getType ) {
			case 'address':
				$outContent = $getContent;
				$outTitle   = $getTitle ? $getTitle : $outContent;
				$outScheme  = 'https://';
				$outIcon    = 'fas fa-map-marker-alt';
				$outClass   = 'address';
				$outURL     = 'google.ru/maps/place/' . self::clearURL( $outContent );
				break;
			case 'email':
				$outContent = $getContent;
				$outTitle   = $getTitle ? $getTitle : $getContent;
				$outScheme  = 'mailto:';
				$outIcon    = 'fas fa-envelope';
				$outClass   = 'email';
				$outURL     = $outContent;
				break;
			case 'tel':
				$outContent = preg_replace( '#[^0-9\+]#', '', $getContent );
				$outTitle   = $getTitle ? $getTitle : $getContent;
				$outScheme  = 'tel:';
				$outIcon    = 'fas fa-phone';
				$outClass   = 'tel';
				$outURL     = $outContent;
				break;
			case 'fax':
				$outContent = preg_replace( '#[^0-9\+]#', '', $getContent );
				$outTitle   = $getTitle ? $getTitle : $getContent;
				$outScheme  = 'fax:';
				$outIcon    = 'fas fa-fax';
				$outClass   = 'fax';
				$outURL     = $outContent;
				break;
			case 'whatsapp':
				$outContent = preg_replace( '#[^0-9]#', '', $getContent );
				$outTitle   = $getTitle ? $getTitle : $outContent;
				$outScheme  = 'https://';
				$outIcon    = 'fab fa-whatsapp';
				$outClass   = 'whatsapp';
				$outURL     = 'api.whatsapp.com/send?phone=' . $outContent;
				break;
			case 'tg':
				$outContent = $getContent;
				$outTitle   = $getTitle ? $getTitle : $outContent;
				$outScheme  = 'tg://';
				$outIcon    = 'fab fa-telegram';
				$outClass   = 'tg';
				$outURL     = 'resolve?domain=' . $outContent;
				break;
			case 'viber':
				$outContent = $getContent;
				$outTitle   = $getTitle ? $getTitle : $outContent;
				$outScheme  = 'viber://';
				$outIcon    = 'fab fa-viber';
				$outClass   = 'viber';
				$outURL     = 'public?id=' . $outContent;
				break;
			case 'skype':
				$outContent = $getContent;
				$outTitle   = $getTitle ? $getTitle : $outContent;
				$outScheme  = 'skype:';
				$outIcon    = 'fab fa-skype';
				$outClass   = 'skype';
				$outURL     = $outContent;
				break;
			default:
				$parser->addTrackingCategory( 'mw-ext-url-error-category' );

				return null;
		}

		// Out HTML.
		$outHTML = '<a class="mw-ext-url mw-ext-url-' . $outClass . '" href="' . $outScheme . $outURL . '" target="_blank" rel="nofollow">';
		$outHTML .= '<i class="fa-icon ' . $outIcon . '"></i>';
		$outHTML .= '<span>' . $outTitle . '</span>';
		$outHTML .= '</a>';

		// Out parser.
		$outParser = $parser->insertStripItem( $outHTML, $parser->mStripState );

		return $outParser;
	}

	/**
	 * Load resource function.
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 *
	 * @return bool
	 */
	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.url.styles' ] );

		return true;
	}
}
