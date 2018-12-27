<?php declare(strict_types=1);

namespace DOMJudgeBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use DOMJudgeBundle\Entity\Contest;
use DOMJudgeBundle\Entity\Team;
use DOMJudgeBundle\Entity\TeamAffiliation;
use DOMJudgeBundle\Entity\TeamCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractExternalIdEntityType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addExternalIdField($builder, Team::class);
        $builder->add('name', TextType::class, [
            'label' => 'Team name',
        ]);
        $builder->add('category', EntityType::class, [
            'class' => TeamCategory::class,
        ]);
        $builder->add('members', TextareaType::class, [
            'required' => false,
        ]);
        $builder->add('affiliation', EntityType::class, [
            'class' => TeamAffiliation::class,
            'required' => false,
            'choice_label' => 'name',
            'placeholder' => '-- no affiliation --',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')->orderBy('a.name');
            },
        ]);
        $builder->add('penalty', IntegerType::class, [
            'label' => 'Penalty time',
        ]);
        $builder->add('room', TextType::class, [
            'label' => 'Location',
            'required' => false,
        ]);
        $builder->add('comments', TextareaType::class, [
            'required' => false,
            'attr' => [
                'rows' => 10,
            ]
        ]);
        $builder->add('contests', EntityType::class, [
            'class' => Contest::class,
            'required' => false,
            'choice_label' => 'name',
            'multiple' => true,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')->where('c.public = false')->orderBy('c.name');
            },
        ]);
        $builder->add('enabled', ChoiceType::class, [
            'expanded' => true,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
        ]);
        $builder->add('addUserForTeam', CheckboxType::class, [
            'label' => 'Add user for this team',
            'required' => false,
        ]);
        $builder->add('users', CollectionType::class, [
            'entry_type' => MinimalUserType::class,
            'entry_options' => ['label' => false],
            'label' => false,
            'required' => false,
        ]);

        $builder->add('save', SubmitType::class);

        // Remove ID field when doing an edit
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Team|null $team */
            $team = $event->getData();
            $form = $event->getForm();

            if ($team && $team->getTeamid() !== null) {
                $form->remove('addUserForTeam');
                $form->remove('users');
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Team::class]);
    }
}
