import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { envoyer } from '../api.js'

function AdminReservations({ utilisateur }) {
  const [reservations, setReservations] = useState([])
  const [erreur, setErreur] = useState('')
  const [chargement, setChargement] = useState(true)

  function charger() {
    fetch('/api/admin/reservations')
      .then((reponse) => {
        if (!reponse.ok) {
          throw new Error('Erreur')
        }
        return reponse.json()
      })
      .then((donnees) => setReservations(donnees))
      .catch(() => setErreur('Impossible de charger les réservations'))
      .finally(() => setChargement(false))
  }

  useEffect(() => {
    if (utilisateur && utilisateur.role === 'admin') {
      charger()
    }
  }, [utilisateur])

  async function annuler(id) {
    if (!window.confirm('Annuler cette réservation ?')) {
      return
    }

    setErreur('')
    const resultat = await envoyer('DELETE', `/api/admin/reservations/${id}`)

    if (resultat.ok) {
      charger()
    } else {
      setErreur(resultat.corps.erreur)
    }
  }

  if (!utilisateur || utilisateur.role !== 'admin') {
    return (
      <section>
        <h1>Administration</h1>
        <p>Cette page est réservée à l'administrateur.</p>
        <Link to="/">Retour à l'accueil</Link>
      </section>
    )
  }

  return (
    <section className="admin">
      <h1>Réservations</h1>

      {erreur && <p role="alert" className="erreur">{erreur}</p>}

      {chargement && !erreur && <p>Chargement…</p>}

      {!chargement && reservations.length === 0 && !erreur && <p>Aucune réservation.</p>}

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
            <p>Client : {reservation.client}</p>
            <p>Du {reservation.date_arrivee} au {reservation.date_depart}</p>
            <p>Total : {reservation.prix_total} €</p>
            <p>Statut : {reservation.statut === 'confirmee' ? 'Confirmée' : 'Annulée'}</p>
            {reservation.statut === 'confirmee' && (
              <button type="button" onClick={() => annuler(reservation.id)}>Annuler cette réservation</button>
            )}
          </div>
        </article>
      ))}
    </section>
  )
}

export default AdminReservations
