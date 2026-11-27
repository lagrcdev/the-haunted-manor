const baseApplication = db.getSiblingDB('haunted_manor')

baseApplication.createUser({
  user: process.env.MONGO_APP_USER,
  pwd: process.env.MONGO_APP_PASSWORD,
  roles: [{ role: 'readWrite', db: 'haunted_manor' }],
})
