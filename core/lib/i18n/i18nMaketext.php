<?php

/**
 * @package  Locale_Maketext
 * @category Internationalisation
 */

/**
 * Extensible text localisation framework
 *
 * @author   Hans Juergen von Lengerke <hans@lengerke.org>
 * @access   public
 */
class i18nMaketext {
	// --------------------------------------------------------------------
	// Member variables
	// --------------------------------------------------------------------
	/**
	 * cache for compiled message functions
	 *
	 * @access  private
	 * @var     array
	 */
	private static $msg_func_cache = array ();

    /**
    * Default to english
    */

	// --------------------------------------------------------------------
	// Public methods
	// --------------------------------------------------------------------
	/**
	 * Getter to message functions cache. Compiles $msg if not contained
	 * in function cache.
	 *
	 * @access   public
	 * @return   function reference to compiled maketext message
	 * @param    string   $msg maketext message
	 */
	public static function getMsgFunc($msg) {
		if (!array_key_exists($msg, self::$msg_func_cache))
			self::setMsgFunc($msg, self::_compile($msg));
		return self::$msg_func_cache[$msg];
	}

	/**
	 * Setter to message functions cache.
	 *
	 * @access  public
	 * @return  void
	 * @param   string    $msg maketext message
	 * @param   function  compiled message function
	 */
	function setMsgFunc($msg, $func) {
		self::$msg_func_cache[$msg] = $func;
	}

	/**
	 * Convenient alias to maketext()
	 *
	 * @access   public
	 * @returns  string processed maketext message
	 * @param    mixed $args a list of arguments of which
	 *                       the first argument is the
	 *                       maketext message and the rest
	 *                       are arguments used to process
	 *                       that message.
	 * @see      maketext
	 */
	function _() {
		$args = func_get_args();
		return self::maketext($args);
	}

	/**
	 * process a maktext message with it's arguments
	 *
	 * <code>
	 *    // generates "2 cats sat on the mat"
	 *    //
	 *    $num_cats = 2;
	 *    $num_mats = 1;
	 *
	 *    $mt->maketext(
	 *       '[quant,_1,cat,cats] sat on the [numerate,_2,mat,mats].',
	 *       $num_cats, $num_mats);
	 * </code>
	 *
	 * @access   public
	 * @returns  mixed processed maketext message or Exception
	 * @param    mixed $args a list of arguments of which the first
	 *                       argument is the maketext message and the rest
	 *                       are arguments used to process that message.
	 */
	public static function maketext() {
		$args = func_get_args();
		if (!$args)
			return '';

		// argument list may have been given as
		// an array, for example when using _()
		if (is_array($args[0]))
			$args = $args[0];

		// split msgid from msg parameters
		$msgid = $args[0];
		$params = array_slice($args, 1);

		// fetch message from dictionary
		$msg = self::fetchMsg($msgid);

		// get message function, may _compile()
		$func = self::getMsgFunc($msg);

		// process message function
		return $func('self::',$params);
	}

	// --------------------------------------------------------------------
	// Maketext functions
	// --------------------------------------------------------------------

	/**
	 * message processing function. Prepends quantifier to
	 * chosen argument.
	 *
	 * @access private
	 */
	public static function quant($args) {
		$num = $args[0];
		$forms = array_slice($args, 1);

		if (!count($forms))
			return $num; // what should this mean?
		if (count($forms) > 2 && $num == 0) // special zeroth case.
			return $forms[2];

		// Normal case
		return $num.' '.self::numerate($args);
	}

	/**
	 * message processing function. Does not prepend quantifier to
	 * chosen argument.
	 *
	 * @access private
	 */
	function numerate($args) {
		$num = $args[0];
		$forms = array_slice($args, 1);

		if (!count($forms))
			return ''; // what should this mean?

		if (count($forms) == 1) { // only headword form specified
			return ($num == 1) ? $forms[0] : $forms[0].'s'; // very cheap hack
		} else { // singular and plural were specified
			return ($num == 1) ? $forms[0] : $forms[1];
		}
	}

	/**
	 * message processing function. Uses sprintf to embed quantifier in
	 * chosen argument.
	 *
	 * @access private
	 */
	function quantf($args) {
		$num = $args[0];
		$forms = array_slice($args, 1);

		if (!count($forms))
			return ''; // what should this mean?
		if (count($forms) > 2 && $num == 0) // special zeroth case.
			return $forms[2];

		if ($num == 1) {
			return sprintf($forms[0], $num);
		} else {
			return sprintf($forms[1], $num);
		}
	}

	// --------------------------------------------------------------------
	// Private methods
	// --------------------------------------------------------------------

	/**
	 * compiles a Maketext message to a PHP function, via PHPs
	 * create_function()
	 *
	 * @access private
	 */
	function _compile($msg) {
		$matches = array ();

		preg_match_all('/[^\~\[\]]+|~.|\[|\]|~|$/', $msg, $matches);

		$in_group = 0;
		$code = array ('return \'\'');
		$chunk = '';

		foreach ($matches[0] as $m) {
			if ($m == '' || $m == '[') {
				if ($in_group) {
					if ($m == '') {
						throw new Exception('Unterminated bracket group in message "'.$msg.'" near "'.$chunk.'"');
					} else {
						throw new Exception('You can not nest bracket groups in message "'.$msg.'" near "'.$chunk.'"');
					}
				} else {
					if ($m == '') {
						// End of Message
					} else {
						$in_group = 1;
					}
					// Add preceding literal to code, if any
					if ($chunk) {
						$code[] = ".'".$chunk."'";
						$chunk = '';
					}
				}

			}
			elseif ($m == ']') {
				if ($in_group) {
					$in_group = 0;

					// Obtain method and args
					$cmatches = preg_split('/(?<!~),/', $chunk);

					if ($cmatches[0] || count($cmatches) > 1) {
						$method = $cmatches[0];
						$params = array_slice($cmatches, 1);

						// Special case, treat [_1,..] as [,_1,...]
						if (preg_match('/^_\d+/', $method)) {
							array_unshift($params, $method);
							$method = '';
						}
						elseif ($method == '*') {
							$method = 'quant';
						}

						// Start code for parameter concatenation or
						// function call with parameters
						if ($method == '') {
							$code[] = '.implode(array(';
						} else {
                            $methods = get_class_methods('i18nMaketext');
							if(in_array($method,$methods)) {
								$code[] = '.i18nMaketext::'.$method.'(array(';
							} else {
								throw new Exception('Method "'.$method.'()" not ' .
                                        'found in class Message "'.$msg.'" near ' .
                                                '"'.$chunk.'"');
							}
						}

						foreach ($params as $param) {
							// Now unescape escaped commas
							$param = preg_replace('/~,/', ',', $param);

							// Add parameter to function call
							// TODO: *_ meaning all message parameters
                            $pmatch = array();
							if (preg_match('/^_(\d+)$/', $param, $pmatch)) {
								$code[] = "\t".'$args['.-- $pmatch[1].'],';
							} else {
								$code[] = "\t\"$param\",";
							}
						}

						$code[] = '))';
					}
					$chunk = '';
				} else {
					throw new Exception('Maketext Syntax Error: Unbalanced \']\' in message "'.$msg.'" near "'.$chunk.'"');
				}
			}
			elseif (substr($m, 0, 1) != '~') {
				// it's stuff not containing "~" or "[" or "]"
				// i.e., a literal blob
				$chunk .= $m;
			}
			elseif ($m == '~~') {
				$chunk .= '~';
			}
			elseif ($m == '~[') {
				$chunk .= '[';
			}
			elseif ($m == '~]') {
				$chunk .= ']';
			}
			elseif ($m == '~,') {
				$chunk .= '~,';
			}
			elseif ($m == '~') {
				// possible only at msg end
				$chunk .= '~';
			} else {
				// It's a "~X" where X is not a special character.
				// Consider it a literal ~ and X
				$chunk .= $m;
			}
		}
		$code[] = ';';
		$func_code = implode($code, "\n");
		//echo "FUNCTION:\n$func_code\n";
		return create_function('$this,$args', implode($code, "\n"));
	}

	/**
	 * Set Locale, use I18Nv2 if available.
	 *
	 * Calls setlocale() if possible through Michael Wallner's I18Nv2
	 * PEAR module for cross platform compatibility.
	 *
	 * Please refer to PHP's {@link http://www.php.net/setlocale
	 * setlocale() function} for an explanation of setlocale()
	 *
	 * @access  public
	 * @param   $locale   Standard Locale Name (e.g. en_US, de_DE)
	 * @return  mixed     locale set by setlocale() or, if that fails,
	 *                    an Exception
	 */
	/*function set_locale($locale) {
		$setlocale = null;

        $setlocale = setlocale(LC_ALL,$locale);

		if(!$setlocale) {
			//throw new Exception("Locale not defined");
			echo $setlocale;
		} else {
			return $setlocale;
		}
	}*/

	/**
	 * A convenience method call to bindtextdomain()
	 *
	 * Please refer to PHP's {@link http://www.php.net/gettext gettext
	 * extension} for an explanation of bindtextdomain()
	 *
	 * @access   public
	 * @return   string the full pathname for the domain currently being set
	 * @param    string $package     package name (also called domain)
	 * @param    string $localedir   directory
	 *
	 */
	/*function bindTextDomain($package, $localedir) {
		return bindtextdomain($package, $localedir);
	}*/

	/**
	 * Fetch message from dictionary.
	 *
	 * Fetches msgstr from MO file using gettext($msgid)
	 *
	 * @access public
	 * @param  string  $msgid   message id for message lookup
	 * @return mixed   $msgstr  message string from MO file, or
	 *                          PEAR_Error if locale is not set
	 *
	 */
	static function fetchMsg($msgid) {
		
			return gettext($msgid);
		
	}
}
?>