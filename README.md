# OC-P3-Exercice1-contact-manager
# Contact Manager en CLI

Contact Manager en CLI est une petite application en ligne de commande écrite en PHP.  
Elle permet de gérer une liste de contacts depuis le terminal.

## Fonctionnalités

L'application permet actuellement de :

- afficher l'aide ;
- lister les contacts ;
- afficher le détail d'un contact ;
- créer un contact ;
- modifier un contact;
- supprimer un contact ;
- quitter le programme.

## Commandes disponibles

Les arguments sont obligatoire sauf les virgules ^^
| Commande | Arguments | Description |
|---|---|---|
| `help` | Aucun | Affiche l'aide |
| `list` | Aucun | Liste tous les contacts |
| `detail` | `[id]` | Affiche le détail d'un contact |
| `create` | `[name], [email], [phone number]` | Crée un nouveau contact |
| `modify` | `[id], [name], [email], [phone number]` | Crée un nouveau contact |
| `delete` | `[id]` | Supprime un contact |
| `quit` | Aucun | Quitte le programme |

## Exemples d'utilisation

Afficher l'aide :

```bash
help
```

Créér un contact :

```bash
create Jean-Pascal M'Baye jp-mb@example.com 0202020202
Contact Jean-Pascal M'Baye jp-mb@example.com 0202020202 créé parfaitement !
```

lister les contacts :

```bash
list 
Liste des contacts :

id, name, email, phone number

1 : User Un : user1@1.com : 0101010101

12 : Gandalf le gris : gandalf@istari.com : 01013021

13 : User Trois : user3@3.com : 0303030303

14 : User Quatre : user4@4.com : 0404040404

15 : User Deux : user2@2.com : 0202020202

16 : Jean-Pascal M'Baye : jp-mb@example.com : 0202020202
```

Modifier un contact :

```bash
modify 16 Jean-Pascal M'Baye jp-mb@example.com 0303030303
16 : Jean-Pascal M'Baye : jp-mb@example.com : 0202020202

Contact 16 Jean-Pascal M'Baye jp-mb@example.com 0303030303 mise à jour parfaitement !
```

Supprimer un contact :

```bash
delete 1
Contact supprimé avec succès 
```