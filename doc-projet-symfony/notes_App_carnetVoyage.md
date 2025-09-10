# Application carnet de voyage



Un utilisateur s'enregistre sur l'application et créer un carnet de voyage dans lequel il pourra poster des messages, des photos et sa localisation.

Il peut partager son carnet.

Les invités peuvent laisser commentaires et likes sur les posts de son carnet.

(Sur la page d'accueil de son carnet, une carte indique chaque localisation)

Accès au post depuis le fil des posts ( ou depuis un point sur la carte)

User créateur d'un carnet : permission admin

User autorisé à le voir : permission visiteur





### **Page d'ACCUEIL (index.html)**

------------------------------------------------------

&nbsp;	Titre : Carnet de Voyage

&nbsp;	AUTHENTIFICATION

 		\*Après authentification -> page PROFIL

&nbsp;	Lien INSCRIPTION

&nbsp;	(Carte avec toutes les localisations des posts pointés)





### **Page INSCRIPTION**

---------------------------------

&nbsp;	Formulaire (Nom – email – password – photo)

&nbsp;		\*Après inscription -> page PROFIL





### **Page de PROFIL**

-----------------------------

&nbsp;	Créer un nouveau carnet de voyage : lien CREATION

&nbsp;	Mes carnets de voyage : Vue des différents carnets (clic -> page CARNET)

&nbsp;	Vue des nouveaux commentaires et likes : Partage (clic -> page CARNET/POST)

&nbsp;	Vue des carnets suivis : Invitations (clic -> page CARNET)

&nbsp;	Gestion permission visiteur : lien PARTAGE



##### 

### **Page CREATION CARNET**

----------------------------------------

&nbsp;	Formulaire (Titre - Ajout photos)

 		\*-> Page CARNET





### **Page CARNET**

----------------------

&nbsp;	Ajouter nouveau post : lien POST

 		\*visible uniquement par l’admin

&nbsp;	(Carte pointée)

&nbsp;	Vue des posts

&nbsp;	•	Ajout de like et commentaire au post





### **Page POST**

------------------

&nbsp;	Formulaire Ajout de post (Titre – texte – photos – localisation)

 		\*-> Page CARNET





### **Page PARTAGE**

------------------------

&nbsp;	Sélection carnet et sélection utilisateur (ajout/suppression)

&nbsp;	Liste utilisateurs pour chaque carnet

