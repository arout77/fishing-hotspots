# Fishing Hotspots 
We all have our favorite spots, or drive past bodies of water and think "I need to come try this spot out sometime!". Then we never do, because we forgot where the spot was. Eventually, we figured out why not just mark these spots right on our topo maps? We then discovered that this was fine for getting us to a general area, but lacked precision. Where exactly was that submerged tree or weed bed that I saw on the topo map..it's around here somewhere! 

If only we could use GPS to save our precise location (or enter a location to save) so that we dont have to guess if we're in the right spot, but have our phone guide us to it...

Ta-da! Introducing our hotspot saver!

### Dev / tech notes
This was originally developed for my own personal use, so I never bothered developing a user authentication system. I added a UID generator that assigns a unique ID to the user and store it in localstorage. That means that the saved locations for the user is restricted to that specific device, they won't be able to sync across multiple devices. Not really a big deal for most people since we typically dont take any electronic devices aside from our phone when we are fishing, but just in case, it is something to be aware of. If you want to be able to sync the data across multiple devices, you'll have to save it to a database -- which also means that you will want to create an actual user registration system.