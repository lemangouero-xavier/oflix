# Interfaces

## Comment c'est construit ?

à la place du mot clé `class` on met `interface`

les functions de cette interface n'ont pas de corps, càd pas de code, pas de `{}`

## Comment ça fonctionne ?

une classe va pouvoir hériter d'une interface.

De la même manière qu'une classe abstraite, quand une classe hérite d'une interface, elle doit implémenter les functions qui sont dans l'interface.

La différence avec une classe abstraite, c'est que l'on peut hériter de plusieur interface.

## A Quoi ça sert ?

Pour l'injection de dépendence, ce qui nous interesse c'est que la classe que l'ont reçoit sache faire quelque chose, qui serait définit dans une interface.

Mais la classe que l'on reçoit peut être dépendente du contexte.

Donc si le contexte change, le framework nous fournirat la bonne classe qui implémente l'interface que l'on demande.

On a donc pas besoin de s'occuper du contexte.

## l'objectif ?

l'objectif d'implementer une interface, ce n'est pas de mettre en commun du code, comme pour l'héritage d'une classe, mais de s'assurer qu'une classe sache faire certaines fonctionnalités.

## Exemples

[source Stackoverflow](https://stackoverflow.com/questions/4961906/when-to-implement-and-extend)

Both Camel and Dog are animals, so they extend Animal class. But only the Dog is a specific kind of Animal that also can be a Pet.

```php
// Contract: a pet should play
public interface Pet {
    public void play(); 
}

// An animal eats and sleeps
class Animal {
    public void eat(){ /*details*/ };
    public void sleep(){ /*details*/ };
}


public class Camel extends Animal {
    // no need to implement eat() and sleep() but
    // either of them can be implemented if needed. i.e. 
    // if Camel eats or sleeps differently from other animals!
}

public class Dog extends Animal implements Pet {    
    public void play() {
       // MUST implement play() details           
    }
}
```
