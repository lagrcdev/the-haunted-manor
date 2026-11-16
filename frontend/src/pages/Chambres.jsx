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
      {chambres.map((chambre) => (
        <article key={chambre.id}>
          <h2>{chambre.nom}</h2>
          <p>Fantôme : {chambre.fantome}</p>
          <p>Niveau de frayeur : {chambre.niveau_frayeur} / 5</p>
          <p>{chambre.prix_nuit} € la nuit</p>
        </article>
      ))}
    </section>
  )
}

export default Chambres
