# Application carnet de voyage



Un utilisateur s'enregistre sur l'application et créer un carnet de voyage dans lequel il pourra ajouter des messages avec photos et localisation (Posts)

Il peut partager son carnet.

Les utilisateurs avec qui il aura partagé son carnet pourront laisser commentaires et likes sur les posts.

Sur la page d'accueil de son carnet, une carte indique chaque localisation,  

Accès au post depuis le fil des posts ( ou depuis un point sur la carte)

Un invité inscrit sur le site peut voir, liké et laisser des commentaires, un invité non-inscrit ne peut que voir le carnet.

ajout API JavaScript Google Maps pour localisation





### **Page d'ACCUEIL (index.html)**

------------------------------------------------------

&nbsp;	Titre : Carnet de Voyage

&nbsp;	AUTHENTIFICATION

 		\*Après authentification -> page PROFIL

&nbsp;	Lien INSCRIPTION

&nbsp;	(Carte avec la localisation de tous les posts de l'application, non cliquables)





### **Page INSCRIPTION**

---------------------------------

&nbsp;	Formulaire (Username – email – password – photo)

&nbsp;		\*Après inscription -> page PROFIL





### **Page de PROFIL**

-----------------------------

&nbsp;	Créer un nouveau carnet de voyage : lien CREATION CARNET

&nbsp;	Mes carnets de voyage : Vue des différents carnets (clic -> page CARNET)

&nbsp;	Vue des nouveaux commentaires et likes : Partage (clic -> page CARNET/POST)

&nbsp;	Vue des carnets suivis : Invitations (clic -> page CARNET)

&nbsp;	Gestion permission visiteur : lien PARTAGE



##### 

### **Page CREATION CARNET**

----------------------------------------

&nbsp;	Formulaire (Titre - Ajout localisation et photos)

 		\*-> Page CARNET





### **Page CARNET**

----------------------

&nbsp;	Ajouter nouveau post : lien CREATION POST

 		\*visible uniquement par le créateur

&nbsp;	Vue des posts	(Carte pointée depuis icône carte sur les posts)

&nbsp;	•	Ajout de like et commentaire au post





### **Page CREATION POST**

------------------

&nbsp;	Formulaire Ajout de post (Titre – texte – photos – localisation)

 		\*-> Page CARNET





### **Page PARTAGE**

------------------------

&nbsp;	Sélection carnet et sélection utilisateur (ajout/suppression)

&nbsp;	Liste utilisateurs pour chaque carnet

