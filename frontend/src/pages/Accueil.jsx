import { Link } from 'react-router-dom'

function Accueil() {
  return (
    <section>
      <h1>Bienvenue à The Haunted Manor</h1>
      <p>Réservez une nuit dans une chambre où un fantôme vous attend.</p>
      <Link to="/chambres">Voir les chambres</Link>
    </section>
  )
}

export default Accueil
