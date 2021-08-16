<?php

namespace App\Datatables;

use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

/**
 * Class UserDatatables
 * @package App\Datatables
 */
class UserDatatables implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable
            ->add('email', TextColumn::class, [
                'label' => 'Email',
                'searchable' => true,
                'orderable' => true,
            ])
            ->add('name', TextColumn::class, [
                'label' => 'pseudo',
                'searchable' => true,
                'orderable' => true,
            ])
            ->add('photo', TwigColumn::class, [
                'label' => 'Photo de profil',
                'template' => 'user/_datatables/_picture.column.datatable.html.twig',
                'visible' => true,
                'orderable' => false,
                'searchable' => false,
                'globalSearchable' => false,
            ])
            ->add('actions', TwigColumn::class, [
                'label' => 'Actions',
                'field' => null,
                'template' => 'user/_datatables/_action.column.datatable.html.twig',
                'visible' => true,
                'orderable' => false,
                'searchable' => false,
                'globalSearchable' => false,
            ])
        ;

        $dataTable->createAdapter(ORMAdapter::class, [
            'entity' => $options['data_class'],
        ]);
    }
}
