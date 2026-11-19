import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'

function AdminReservations({ utilisateur }) {
  const [reservations, setReservations] = useState([])
  const [erreur, setErreur] = useState('')

  useEffect(() => {
    if (utilisateur && utilisateur.role === 'admin') {
      fetch('/api/admin/reservations')
        .then((reponse) => {
          if (!reponse.ok) {
            throw new Error('Erreur')
          }
          return reponse.json()
        })
        .then((donnees) => setReservations(donnees))
        .catch(() => setErreur('Impossible de charger les réservations'))
    }
  }, [utilisateur])

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
      <h1>Toutes les réservations</h1>

      {erreur && <p role="alert" className="erreur">{erreur}</p>}

      {reservations.length === 0 && !erreur && <p>Aucune réservation.</p>}

      {reservations.map((reservation) => (
        <article key={reservation.id} className="reservation">
          <h2>{reservation.chambre}</h2>
          <p>Client : {reservation.client}</p>
          <p>Du {reservation.date_arrivee} au {reservation.date_depart}</p>
          <p>Total : {reservation.prix_total} €</p>
          <p>Statut : {reservation.statut}</p>
        </article>
      ))}
    </section>
  )
}

export default AdminReservations
