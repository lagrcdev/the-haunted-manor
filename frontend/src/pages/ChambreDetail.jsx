import { useEffect, useState } from 'react'
import { Link, useParams } from 'react-router-dom'

function ChambreDetail() {
  const { id } = useParams()
  const [chambre, setChambre] = useState(null)
  const [erreur, setErreur] = useState('')

  useEffect(() => {
    fetch(`/api/chambres/${id}`)
      .then((reponse) => {
        if (!reponse.ok) {
          throw new Error('Chambre introuvable')
        }
        return reponse.json()
      })
      .then((donnees) => setChambre(donnees))
      .catch(() => setErreur('Chambre introuvable'))
  }, [id])

  if (erreur) {
    return <p>{erreur}</p>
  }

  if (!chambre) {
    return <p>Chargement...</p>
  }

  return (
    <section className="detail-chambre">
      {chambre.photo && (
        <img
          src={`/images/${chambre.photo}`}
          alt={`Illustration de ${chambre.nom}`}
          width="1200"
          height="800"
          className="photo-chambre"
        />
      )}
      <h1>{chambre.nom}</h1>
      <p>Fantôme : {chambre.fantome}</p>
      <p>{chambre.description}</p>
      <p>Niveau de frayeur : {chambre.niveau_frayeur} / 5</p>
      <p>{chambre.prix_nuit} € la nuit</p>
      <Link to="/chambres" className="bouton">Retour aux chambres</Link>
    </section>
  )
}

export default ChambreDetail
