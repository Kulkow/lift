<?php defined('SYSPATH') OR die('No direct access allowed.');

class Jevix
{
	/**
	 * ���������� ��������� ������
	 */
	static public function limit_chars(ODM $object, $value, $source, $limit, $end_char = '', $preserve_words = TRUE)
	{
		if (empty($value))
		{
			$value = Text::limit_chars($object->$source, $limit, $end_char, $preserve_words);
		}
		return $value;
	}

	/**
	 * ��������������
	 */
	static public function translit($object, $value, $source)
	{
		if (empty($value))
		{
			$value = Text::translit($object->$source);
		}
		return $value;
	}

	/**
	 * ��������� html-������
	 */
	static public function parse($value, $preset = NULL)
	{


		return $value;
	}

} // End Jevix