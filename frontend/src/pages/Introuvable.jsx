import { Link } from 'react-router-dom'

function Introuvable() {
  return (
    <section>
      <h1>Page introuvable</h1>
      <p>Cette page n'existe pas.</p>
      <Link to="/">Retour à l'accueil</Link>
    </section>
  )
}

export default Introuvable
