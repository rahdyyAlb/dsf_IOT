<?php

namespace App\Form;

use App\Entity\Transactions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionsType extends AbstractType
{
	public function buildForm (FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('transactions_date')
			->add('total_amount')
			->add('cash_amount')
			->add('card_amount')
			->add('cheque_amount');
	}

	public function configureOptions (OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Transactions::class,
		]);
	}
}
