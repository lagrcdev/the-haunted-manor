import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { envoyer } from '../api.js'

function MesReservations({ utilisateur }) {
  const [reservations, setReservations] = useState([])
  const [erreur, setErreur] = useState('')

  function charger() {
    fetch('/api/reservations')
      .then((reponse) => {
        if (!reponse.ok) {
          throw new Error('Erreur')
        }
        return reponse.json()
      })
      .then((donnees) => setReservations(donnees))
      .catch(() => setErreur('Impossible de charger vos réservations'))
  }

  useEffect(() => {
    if (utilisateur) {
      charger()
    }
  }, [utilisateur])

  async function annuler(id) {
    const resultat = await envoyer('DELETE', `/api/reservations/${id}`)

    if (resultat.ok) {
      charger()
    } else {
      setErreur(resultat.corps.erreur)
    }
  }

  if (!utilisateur) {
    return (
      <section>
        <h1>Mes réservations</h1>
        <p>
          Vous devez être connecté. <Link to="/connexion">Se connecter</Link>
        </p>
      </section>
    )
  }

  return (
    <section className="espace-client">
      <h1>Mes réservations</h1>

      {erreur && <p role="alert" className="erreur">{erreur}</p>}

      {reservations.length === 0 && !erreur && <p>Vous n'avez pas encore de réservation.</p>}

      {reservations.map((reservation) => (
        <article key={reservation.id} className="reservation">
          <h2>{reservation.chambre}</h2>
          {reservation.photo && (
            <img
              src={`/uploads/${reservation.photo}`}
              alt={`Illustration de ${reservation.chambre}`}
              width="1200"
              height="800"
              loading="lazy"
              className="reservation-photo"
            />
          )}
          <div className="reservation-texte">
            <p>Du {reservation.date_arrivee} au {reservation.date_depart}</p>
            <p>Total : {reservation.prix_total} €</p>
            <p>Statut : {reservation.statut === 'confirmee' ? 'Confirmée' : 'Annulée'}</p>
            {reservation.statut === 'confirmee' && (
              <button type="button" onClick={() => annuler(reservation.id)}>Annuler ma réservation</button>
            )}
          </div>
        </article>
      ))}
    </section>
  )
}

export default MesReservations
