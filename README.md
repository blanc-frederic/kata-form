# Kata Alpha - Utilisation des form Symfony

## Installation

> docker compose run composer install

## Integrer Symfony Form

### Etape 1 : ajout du composant form

> docker compose run composer require form

### Etape 2 : créer un ArticleType

```php
<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('date', DateType::class)
            ->add('content', TextType::class)
        ;
    }
}
```

NB : Options intéressantes pour les champs (3e argument de ->add()):

- required : (bool) champ avec un **rendu** comme obligatoire
- mapped : (bool) champ envoyé aux data issues du form
- label : (string) label a générer en face du champ
- attr : (array) injecter des attributs html au champs

### Etape 3 : Modifier le controller

```php
$form = $this->createForm(ArticleType::class, $article);
$form->add('save', SubmitType::class);

return $this->render('exemple.html.twig', [
    'article' => $article,
    'form' => $form->createView(), // l'appel au ->createView() n'est plus nécessaire en SF 6.2
]);
```

### Etape 4 : Modifier le template

```twig
{% block body %}
<h1>Edition de l'article {{ article.title }}</h1>
{{ form(form) }}
{% endblock %}
```

### Etape 5 : Prise en compte des modifications

Modifier le comportement du controller :

```php
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
    $data = $form->getData();
    // @todo faire quelque chose de ces données
    return $this->redirectToRoute('target');
}

return $this->render('exemple.html.twig', [
    'article' => $article,
    'form' => $form,
]);
```

### Etape 6 : utiliser directement le DTO

Modifier le ArticleType : 

```php
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Article::class,
    ]);
}
```

## Utiliser le rendu Bootstrap 5

```yaml
# config/packages/twig.yaml
twig:
    form_themes: ['bootstrap_5_layout.html.twig']
```

## Valider les données

### Composant Validator

> docker compose run composer require validator

### Ajouter des contraintes directement sur le DTO

```php
[...]
use Symfony\Component\Validator\Constraints as Assert;
[...]
#[Assert\NotBlank]
public string $title,
#[Assert\NotBlank]
#[Assert\Type(\DateTimeInterface::class)]
public DateTimeInterface $date,
#[Assert\NotBlank]
public string $content
```

### ou dans le ArticleType

```php
public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('title', TextType::class, [
            'required' => true,
            'constraints' => [new Assert\NotBlank()],
        ])
        [...]
    ;
}
```
