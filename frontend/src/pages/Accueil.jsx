import { Link } from 'react-router-dom'

function Accueil() {
  return (
    <section className="accueil">
      <h1><span className="police-texte">Bienvenue à</span> The Haunted Manor</h1>
      <img
        src="/images/manoir.jpg"
        alt="Le manoir de l'hôtel au crépuscule, entouré de brouillard, avec des fenêtres éclairées"
        width="1600"
        height="900"
        className="banniere"
      />
      <p>
        The Haunted Manor est un vieux manoir du XIXe siècle. Pendant des années, il a accueilli des voyageurs, des familles et des mariés. Certains ne sont jamais vraiment partis. Aujourd'hui, nous vous proposons de passer une nuit dans leurs chambres.
      </p>
      <p>Chaque chambre a son fantôme et son histoire.</p>
      <p>
        Dans la chambre de la mariée éternelle, Éléonore de Valombre, morte en 1892, pleure encore derrière le miroir. Dans la chambre des murmures, le petit Gabriel Moreau, disparu en 1911, joue à cache-cache à minuit. Dans la chambre du fauteuil maudit, Victor Blackwood, l'ancien propriétaire, refuse toujours de quitter les lieux.
      </p>
      <p>
        Chacune a un niveau de frayeur, noté de 1 à 5, pour que vous sachiez à quoi vous attendre. La chambre des murmures est la plus calme. Celle du fauteuil maudit est la plus éprouvante : elle est réservée à ceux qui n'ont pas peur.
      </p>
      <Link to="/chambres" className="bouton">Voir les chambres</Link>
    </section>
  )
}

export default Accueil
