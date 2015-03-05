<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_1C extends Controller_Layout
{

	/**
    id – уникальный номер БК,
    ball – кол-во,  начисленных баллов,
    email – email пользователя (необязательно, если есть в анкете),
    phone - телефон пользователя (необязательно, если есть в анкете),
    activate – дата (дд.мм.ГГГГ ЧЧ:СС), когда будет активирована карта (если она не активирована)??
    data – дата (дд.мм.ГГГГ ЧЧ:СС) совершения операции.
    type – тип операции (1 – начисление, 2 – списание, 3 – обнуление, …..)
    code – уникальный код трансакции,
    hash – хеш запроса (алгоритм выше).

    */
    public function before()
	{
		parent::before();

	}

	public function action_index()
	{
		if (HTTP_Request::POST == $this->request->method())
		{
			try
			{
				$values = $this->request->post();

				$event_type = ORM::factory('event_type');
				$event_type->values($values)->create();

                Controller::redirect('admin/event/type');
			}
			catch (ORM_Validation_Exception $e)
			{
				$_REQUEST = Arr::merge($_REQUEST, $values);
				$errors = $e->errors('event');
			}
		}
        $this->template->content = View::factory('admin/1c')->bind('errors', $errors);
	}


}