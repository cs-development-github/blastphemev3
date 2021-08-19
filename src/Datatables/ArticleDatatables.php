<?php

namespace App\Datatables;

use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

/**
 * Class ArticleDatatables
 * @package App\Datatables
 */
class ArticleDatatables implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options): void
    {
        $dataTable
            ->add('titre', TextColumn::class, [
                'label' => 'Titre',
                'searchable' => true,
                'orderable' => true,
            ])
            ->add('typeId', TextColumn::class, [
                'label' => 'Type',
                'searchable' => true,
                'orderable' => true,
                'field' => 'type.libelle',
            ])
            ->add('auteurId', TextColumn::class, [
                'label' => 'Auteur',
                'searchable' => true,
                'orderable' => true,
                'field' => 'auteur.name',
            ])
            ->add('image', TwigColumn::class, [
                'label' => 'Image de couverture',
                'template' => 'article/_datatables/_picture.column.datatable.html.twig',
                'visible' => true,
                'orderable' => false,
                'searchable' => false,
                'globalSearchable' => false,
            ])
            ->add('parution', TwigColumn::class, [
                'label' => 'Parution',
                'field' => null,
                'template' => 'article/_datatables/_parution.column.datatable.html.twig',
                'visible' => true,
                'orderable' => false,
                'searchable' => false,
                'globalSearchable' => false,
            ])
            ->add('actions', TwigColumn::class, [
                'label' => 'Actions',
                'field' => null,
                'template' => 'article/_datatables/_action.column.datatable.html.twig',
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
