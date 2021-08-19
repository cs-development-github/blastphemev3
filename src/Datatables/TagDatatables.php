<?php

namespace App\Datatables;

use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

/**
 * Class TagDatatables
 * @package App\Datatables
 */
class TagDatatables implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable
            ->add('libelle', TextColumn::class, [
                'label' => 'Libelle',
                'searchable' => true,
                'orderable' => true,
            ])
            ->add('actions', TwigColumn::class, [
                'label' => 'Actions',
                'field' => null,
                'template' => 'tag/_datatables/_action.column.datatable.html.twig',
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
