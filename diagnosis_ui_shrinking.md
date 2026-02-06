# Diagnostic - Problème d'Interface qui Rétrécit

Après analyse approfondie de la structure des assets et des layouts, voici les causes probables du "rétrécissement" de l'interface après un clic.

## 🔍 Constats Techniques

### 1. Duplication Massive des Fichiers CSS (Coupable n°1)
Il y a un mélange entre le système d'assets de Yii2 et des inclusions manuelles :
- **AppAsset.php** : Déclare Bootstrap et YiiAsset.
- **includes/links.php** : Inclut manuellement `bootstrap.min.css`, `mdb.min.css`, et `main.css`.
- **administrator_base.php** : Contient plus de 300 lignes de CSS en ligne (`<style>`).

> [!WARNING]
> Lorsque Bootstrap est chargé deux fois, les règles de "breakpoints" (largeur des containers) et de "box-sizing" peuvent s'écraser. Au premier chargement, le navigateur gère, mais un clic déclenche souvent un "reflow" qui applique les styles différemment.

### 2. Gestion Inconsistante des Layouts
- `main.php` utilise `AppAsset::register($this)`.
- `administrator_base.php` ne le fait pas, mais inclut manuellement `includes/links.php`.
Certains widgets Yii (comme GridView ou ActiveForm) forcent le chargement des assets Yii si nécessaire, ce qui ré-introduit les fichiers en double de manière imprévisible au clic.

### 3. Le "Loading Overlay" et le Viewport
Le script dans `administrator_base.php` affiche un `#loading-overlay` à chaque clic sur un lien ou bouton.
```javascript
$('#loading-overlay').css('display', 'flex').hide().fadeIn(300);
```
Cet overlay a une `position: fixed` et un `width: 100%`. S'il n'est pas coordonné avec la barre de défilement (scrollbar), il peut forcer le navigateur à recalculer la largeur du viewport "visible", provoquant un saut (le rétrécissement) de tous les éléments ayant une largeur relative (%, vh, vw).

### 4. Conflits de "Box-Sizing"
MDB (Material Design Bootstrap) et Bootstrap 3/4 ont des manières différentes de gérer la largeur des éléments. En les mélangeant manuellement sans AssetBundle, le "box-sizing: border-box" peut sauter après certains événements DOM déclenchés par JS.

---

## 💡 Pourquoi ça rétrécit spécifiquement "après un clic" ?

Lorsqu'on clique :
1. Le **JS du Loading Overlay** s'active.
2. Si le clic est sur un bouton de formulaire, Yii2 peut déclencher une **validation JS**.
3. Ces actions forcent le navigateur à effectuer un **"Paint"** (redessin).
4. Durant ce Paint, les styles en double ou conflictuels sont ré-évalués. Le navigateur peut alors décider que le viewport est plus étroit (à cause de la scrollbar ou du blur de l'overlay) et switcher le `.container` sur un breakpoint inférieur (ex: de 1140px à 960px).

## ✅ Recommandations (Comme suggéré dans votre guide)

1. **Unifier les Assets** : Supprimer les inclusions manuelles dans `links.php` et tout passer dans `AppAsset.php`.
2. **Nettoyer les Layouts** : Enregistrer `AppAsset` dans `administrator_base.php` pour que Yii gère l'ordre des styles.
3. **Extraire le CSS en ligne** : Sortir les 300 lignes de CSS du layout vers un fichier `.css` externe géré par l'AssetBundle.
4. **Optimiser l'Overlay** : S'assurer que l'affichage de l'overlay ne change pas l'état du scroll de la page principale.
