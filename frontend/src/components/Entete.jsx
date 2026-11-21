import { useState } from 'react'
import { Link, NavLink } from 'react-router-dom'

function Entete({ utilisateur, onDeconnexion }) {
  const [menuOuvert, setMenuOuvert] = useState(false)

  return (
    <header>
      <Link to="/">
        <img src="/images/logo.png" alt="The Haunted Manor, retour à l'accueil" width="120" />
      </Link>

      <button
        type="button"
        className="bouton-menu"
        aria-label="Menu"
        aria-expanded={menuOuvert}
        aria-controls="menu-principal"
        onClick={() => setMenuOuvert(!menuOuvert)}
      >
        <span></span>
        <span></span>
        <span></span>
      </button>

      <nav id="menu-principal" className={menuOuvert ? 'ouvert' : ''} onClick={() => setMenuOuvert(false)}>
        <NavLink to="/">Accueil</NavLink>
        <NavLink to="/chambres">Chambres</NavLink>
        {utilisateur && <NavLink to="/mes-reservations">Mes réservations</NavLink>}
        <NavLink to="/livre-d-or">Livre d'or</NavLink>

        {utilisateur ? (
          <>
            {utilisateur.role === 'admin' && (
              <>
                <NavLink to="/admin" end>Gestion des réservations</NavLink>
                <NavLink to="/admin/chambres">Gestion des chambres</NavLink>
              </>
            )}
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
