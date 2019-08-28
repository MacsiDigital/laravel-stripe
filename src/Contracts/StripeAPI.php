<?php

namespace MacsiDigital\Stripe\Contracts;

interface StripeAPI
{

    public function setModel($model);

    public function getModel();

    public function get($wheres, $limit=0, $pagination='');

    public function insert(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);
}