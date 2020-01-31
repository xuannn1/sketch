// a global store is used to save some temporary data shared between components. (e.g. poster name for dialogue)
// when some data is hard to pass around between components, (e.g. one of the component is in route root level), we can put the data in the temporary store

export class Store {
  public dialogue:{
    recipientName:string,
  };

  public constructor() {
    this.dialogue = {
      recipientName:'EMOL',
    };
  }

}
