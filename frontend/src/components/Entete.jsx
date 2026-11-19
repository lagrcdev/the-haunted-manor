import { Link, NavLink } from 'react-router-dom'

function Entete({ utilisateur, onDeconnexion }) {
  return (
    <header>
      <Link to="/">
        <img src="/images/logo.png" alt="The Haunted Manor, retour à l'accueil" width="120" />
      </Link>

      <nav>
        <NavLink to="/">Accueil</NavLink>
        <NavLink to="/chambres">Chambres</NavLink>
        <NavLink to="/livre-d-or">Livre d'or</NavLink>

        {utilisateur ? (
          <>
            <NavLink to="/mes-reservations">Mes réservations</NavLink>
            {utilisateur.role === 'admin' && (
              <>
                <NavLink to="/admin">Gérer les réservations</NavLink>
                <NavLink to="/admin/chambres">Gérer les chambres</NavLink>
              </>
            )}
            <span>Bonjour {utilisateur.nom}</span>
            <button type="button" onClick={onDeconnexion}>Déconnexion</button>
          </>
        ) : (
          <>
            <NavLink to="/inscription">Inscription</NavLink>
            <NavLink to="/connexion">Connexion</NavLink>
          </>
        )}
      </nav>
    </header>
  )
}

export default Entete
