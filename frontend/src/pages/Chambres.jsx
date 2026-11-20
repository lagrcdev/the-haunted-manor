import { Link } from 'react-router-dom'
import { useEffect, useState } from 'react'

function Chambres() {
  const [chambres, setChambres] = useState([])
  const [erreur, setErreur] = useState('')

  useEffect(() => {
    fetch('/api/chambres')
      .then((reponse) => {
        if (!reponse.ok) {
          throw new Error('Erreur serveur')
        }
        return reponse.json()
      })
      .then((donnees) => setChambres(donnees))
      .catch(() => setErreur('Impossible de charger les chambres'))
  }, [])

  if (erreur) {
    return <p>{erreur}</p>
  }

  return (
    <section>
      <h1>Nos chambres</h1>
      <div className="liste-chambres">
        {chambres.map((chambre) => (
          <article key={chambre.id} className="carte">
            {chambre.photo && (
              <img
                src={`/uploads/${chambre.photo}`}
                alt={`Illustration de ${chambre.nom}`}
                width="1200"
                height="800"
                loading="lazy"
              />
            )}

            <h2>{chambre.nom}</h2>
            <p>Cette chambre est habitée par {chambre.fantome}</p>
            <p>2 personnes</p>
            <p>Niveau de frayeur : {chambre.niveau_frayeur} / 5</p>
            <p>{chambre.prix_nuit} € la nuit</p>
            <Link to={`/chambres/${chambre.id}`} className="bouton">Voir la chambre</Link>
          </article>
        ))}
      </div>
    </section>
  )
}

export default Chambres
